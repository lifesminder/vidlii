<?php
    class ffmpeg {
		public  $Info,
                $Location,
                $Resolution,
                $Framerate,
                $SampleRate,
                $Output,
                $Bitrate,
                $AudioBitrate,
                $CRF,
                $HD,

                $FFMPEG     = "/usr/bin/ffmpeg",
                $FFPROBE    = "/usr/bin/ffprobe";

                //$FFMPEG     = "C:/ffmpeg/bin/ffmpeg.exe",
                //$FFPROBE    = "C:/ffmpeg/bin/ffprobe.exe";

        public function Get_Length($Echoseconds = NULL){

            $ffmpegoutput = shell_exec("$this->FFPROBE -i ". $this->Location ." 2>&1");

            $search='/Duration: (.*?),/';
            preg_match($search, $ffmpegoutput, $matches);
            if (isset($matches[0]) && $matches[1] !== "N/A") {
                $explode = explode(':', $matches[1]);
                $hours = $explode[0];
                $minutes = $explode[1];
                $seconds = substr($explode[2], 0, strpos($explode[2], "."));
                $minutes += $hours * 60;
                if ($Echoseconds == false) {
                    return $minutes . "." . $seconds;
                } else {
                    $seconds += $minutes * 60;
                    return $seconds;
                }
            }
            else {
                return false;
            }
        }
		
        public function Get_Info() {
            $ffmpegoutput = shell_exec("$this->FFPROBE -i ". $this->Location ." -v quiet -print_format json -show_format -show_streams 2>&1");
			$this->Info = json_decode($ffmpegoutput);
        }

		public function Resize($h_res) {
			// Set possible resolutions [Width => Height]
			$resolutions = [
				256 => 144,
				426 => 240,
				640 => 360,
				854 => 480,
				1280 => 720
			];
			
			foreach($this->Info->streams as $s) {
				if ($s->codec_type == "video") {
					$vstream = $s;
					break;
				}
			}
			
			$vwidth = $vstream->width;
			$vheight = $vstream->height;
			$aspect = $vstream->display_aspect_ratio??($vwidth.":".$vheight);
			if ($aspect != "0:1") { // Correct resolution based on Aspect Ratio
				if (strpos($aspect, ":") !== false) {
					$aspect = explode(":", $aspect);
					$aspect = (float)((int)$aspect[0] / (int)$aspect[1]);
				} else {
					$aspect = (float)$aspect;
				}
				
				if ($vheight * $aspect > $vwidth) {
					$vwidth = round($vheight * $aspect);
				} else {
					$vheight = round($vwidth / $aspect);
				}	
			}
			
			// Pick best resolution and resize file accordingly
			$w_res = 256;
			$resize = true;
			foreach($resolutions as $w => $h) {
				if ($h > $h_res) break;
				else $w_res = $w;
				
				if ($vwidth <= $w && $vheight <= $h) {
					$width = $vwidth;
					$height = $vheight;
					$resize = false;
					break;
				}
			}
			
			// If video doesn't fit in the resolution, resize
			if ($resize) {
				$height = $resolutions[$w_res];
				$width = (int)($vwidth * ($height / $vheight));
				
				if ($width > $w_res) {
					$width = $w_res;
					$height = (int)($vheight * ($width / $vwidth));
				}
			}
			
			// Turn uneven numbers into even numbers for conversion
			if ($width % 2 == 1) $width++;
			if ($height % 2 == 1) $height++;
			
			// Set resolution
			$this->Resolution = $width."x".$height;
			$this->HD = $resolutions[$w_res] >= 720 ? true : false;
		}
		
        public function Convert() {
            // Using -max_muxing_queue_size 102400 will make sure many files that require a large muxing queue to encode.
            // It's not a silver bullet, but it will fix issues with many files on ffmpeg 4.1.8.
            // Newer versions of ffmpeg don't have this problem.
			$command = "$this->FFMPEG -i ". $this->Location ." -c:v libx264 -profile:v main -level 3.1 -preset veryfast -s ". $this->Resolution ." -crf ". $this->CRF ." -r ". $this->Framerate ." -pix_fmt yuv420p -b:a ". $this->AudioBitrate ." -ar ". $this->SampleRate ." -strict -1 -movflags +faststart -max_muxing_queue_size 102400 ".$this->Output; // Don't use -c:a, it'll select an aac encoder as the default one for mp4 files
			echo "Convertion started: $command\n\n";
			exec($command, $output, $success);
			return ($success == 0 ? true : false);
		}

        public function Thumbnail($Resolution = NULL,$sec,$Output) { // Old Method
            if (!isset($Resolution)) {
                $Resolution = $this->Resolution;
            }
			
            shell_exec("$this->FFMPEG -i ". $this->Location ." -an -ss $sec -s $Resolution $Output");
        }
		
        public function Make_Thumbnails($sec, $URL) { // New Method, generates preview images
			// Take Screenshot
			$Output = "../usfi/prvw/$URL.temp.jpg";
            shell_exec("$this->FFMPEG -i ". $this->Location ." -an -ss $sec -frames:v 1 $Output");
			
			// Make Thumbnails
			for ($i=0; $i < 2; $i++) {
				if ($i == 0) {
					$DIR = "thmp";
					$WID = 256;
					$HEI = 144;
				} else {
					$DIR = "prvw";
					$WID = 856;
					$HEI = 480;
				}
				
				$Uploader = new upload($Output);
				$Uploader->file_new_name_body = $URL;
				$Uploader->image_resize = true;
				$Uploader->file_overwrite          = true;
				$Uploader->image_x                 = $WID;
				$Uploader->image_y                 = $HEI;
				$Uploader->image_background_color  = '#000000';
				$Uploader->image_convert           = 'jpg';
				$Uploader->file_max_size           = 1000000;
				$Uploader->image_ratio_crop        = true;
				$Uploader->jpeg_quality            = 65;
				$Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
				$Uploader->process("../usfi/$DIR/");
				if (!$Uploader->processed) {
					rename("../usfi/$DIR/".$URL."_.jpg","../usfi/$DIR/$URL.jpg");
				}
			}
			
			unlink($Output);
        }
    }
