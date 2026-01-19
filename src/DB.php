<?php
    namespace Vidlii\Vidlii;

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/init.php";

    class DB {
        public $api, $lastID, $RowNum, $active = false;
        protected $Connection;

        function __construct(bool $Show_Errors = false) {
            $this->api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);
            $this->$Show_Errors = $Show_Errors;
        }

        public function query($query) {
            return $this->api->db($query);
        }

        public function execute(string $SQL, bool $Single = false, array $Execute = []): array {
            // Quickly replace all prepending ":" with nothing,
            // as DBAL doesn't bind those values
            $normalized = [];
            foreach($Execute as $key => $value) {
                if(is_string($key) && str_starts_with($key, ':')) {
                    $key = substr($key, 1);
                }
                $normalized[$key] = $value;
            }
            // Do the rest
            $Single = !$Single; // VidLii bug, as parameter's boolean value does reverse.
            $execution = $this->api->db($SQL, $Single, $normalized);
            if($execution["status"] == -1) {
                return [];
            }
            $this->RowNum = $execution["count"] ?? 0;
            $this->lastID = $execution["last"] ?? -1;
            if($this->RowNum == 0) {
                return [];
            } else {
                return $execution["data"] ?? [];
            }
        }

        public function modify(string $SQL, array $Execute = []): bool {
            // Quickly replace all prepending ":" with nothing,
            // as DBAL doesn't bind those values
            $normalized = [];
            foreach($Execute as $key => $value) {
                if(is_string($key) && str_starts_with($key, ':')) {
                    $key = substr($key, 1);
                }
                $normalized[$key] = $value;
            }
            // Do the rest
            $execution = $this->api->db($SQL, false, $normalized);
            return (bool)($execution["status"] == 1);
        }

        public function last_id() {
            return $this->lastID;
        }
    }
