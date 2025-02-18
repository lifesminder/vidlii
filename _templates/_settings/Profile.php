<? if ($Channel_Version != 2) : ?>
<style>
	.p_text_area {
		border: 1px solid #d5d5d5;
		padding: 3px 4px;
		border-radius: 3px;
		outline: 0;
		resize: none;
		overflow: hidden;
	}
	.p_text_area:hover {
		border: 1px solid #ababab;
	}

	.p_text_area:focus {
		border: 1px solid #9d9efd;
	}
</style>
<h4>Profile Setup</h4>
<form action="/my_profile" method="POST">
	<div style="margin:15px 0 21px">
		<label for="st_in" style="display:block;font-size:13px;position:relative;bottom:1px">About You:</label>
		<textarea class="p_text_area" name="about" maxlength="500" style="width:300px;height:79px"><?= $Info["about"] ?></textarea><br><br>
		<label for="st_we" style="display:block;font-size:13px;position:relative;bottom:1px">Website:</label>
		<input type="text" id="st_we" name="website" value="<?= htmlspecialchars($Info["website"]) ?>" maxlength="128" size="36"><br>
	</div>
	<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
		<img src="/img/clp00.png">
		<span class="u_sct_hd">Personal Details</span>
	</div>
	<div style="display:none;position:relative;left:6.5px">
		<label style="display:block;font-size:13px;position:relative;bottom:1px">Birthday:</label>
		<select name="month">
			<? foreach($Months_Array as $item => $value) : ?>
				<option value="<?= $value ?>"<? if ($value == $Birth_Month) : ?> selected<? endif ?>><?= $item ?></option>
			<?php endforeach ?>
		</select>
		<select name="day">
			<? for ($x = 1; $x <= 31; $x++) : ?>
				<option value="<?= $x ?>"<? if ($x == $Birth_Day) : ?> selected<? endif ?>><?= $x ?></option>
			<? endfor ?>
		</select>
		<select name="year">
			<? for($x = date("Y");$x >= 1910;$x--) : ?>
				<option value="<?= $x ?>"<? if ($x == $Birth_Year) : ?> selected<? endif ?>><?= $x ?></option>
			<? endfor ?>
		</select><br><br>
		<label style="display:block;font-size:13px;position:relative;bottom:1px">Country:</label>
		<select name="country">
			<option value="AF" <? if($Info["country"] == "AAF"): ?>selected<? endif ?>>Afghanistan</option>
			<option value="AX" <? if($Info["country"] == "AX"): ?>selected<? endif ?>>Aland Islands</option>
			<option value="AL" <? if($Info["country"] == "AL"): ?>selected<? endif ?>>Albania</option>
			<option value="DZ" <? if($Info["country"] == "DZ"): ?>selected<? endif ?>>Algeria</option>
			<option value="AS" <? if($Info["country"] == "AS"): ?>selected<? endif ?>>American Samoa</option>
			<option value="AD" <? if($Info["country"] == "AD"): ?>selected<? endif ?>>Andorra</option>
			<option value="AO" <? if($Info["country"] == "AO"): ?>selected<? endif ?>>Angola</option>
			<option value="AI" <? if($Info["country"] == "AI"): ?>selected<? endif ?>>Anguilla</option>
			<option value="AQ" <? if($Info["country"] == "AQ"): ?>selected<? endif ?>>Antarctica</option>
			<option value="AG" <? if($Info["country"] == "AG"): ?>selected<? endif ?>>Antigua and Barbuda</option>
			<option value="AR" <? if($Info["country"] == "AR"): ?>selected<? endif ?>>Argentina</option>
			<option value="AM" <? if($Info["country"] == "AM"): ?>selected<? endif ?>>Armenia</option>
			<option value="AW" <? if($Info["country"] == "AW"): ?>selected<? endif ?>>Aruba</option>
			<option value="AU" <? if($Info["country"] == "AU"): ?>selected<? endif ?>>Australia</option>
			<option value="AT" <? if($Info["country"] == "AT"): ?>selected<? endif ?>>Austria</option>
			<option value="AZ" <? if($Info["country"] == "AZ"): ?>selected<? endif ?>>Azerbaijan</option>
			<option value="BS" <? if($Info["country"] == "BS"): ?>selected<? endif ?>>Bahamas</option>
			<option value="BH" <? if($Info["country"] == "BH"): ?>selected<? endif ?>>Bahrain</option>
			<option value="BD" <? if($Info["country"] == "BD"): ?>selected<? endif ?>>Bangladesh</option>
			<option value="BB" <? if($Info["country"] == "BB"): ?>selected<? endif ?>>Barbados</option>
			<option value="BY" <? if($Info["country"] == "BY"): ?>selected<? endif ?>>Belarus</option>
			<option value="BE" <? if($Info["country"] == "BE"): ?>selected<? endif ?>>Belgium</option>
			<option value="BZ" <? if($Info["country"] == "BZ"): ?>selected<? endif ?>>Belize</option>
			<option value="BJ" <? if($Info["country"] == "BJ"): ?>selected<? endif ?>>Benin</option>
			<option value="BM" <? if($Info["country"] == "BM"): ?>selected<? endif ?>>Bermuda</option>
			<option value="BT" <? if($Info["country"] == "BT"): ?>selected<? endif ?>>Bhutan</option>
			<option value="BO" <? if($Info["country"] == "BO"): ?>selected<? endif ?>>Bolivia</option>
			<option value="BQ" <? if($Info["country"] == "BQ"): ?>selected<? endif ?>>Bonaire</option>
			<option value="BA" <? if($Info["country"] == "BA"): ?>selected<? endif ?>>Bosnia and Herzegovina</option>
			<option value="BW" <? if($Info["country"] == "BW"): ?>selected<? endif ?>>Botswana</option>
			<option value="BV" <? if($Info["country"] == "BV"): ?>selected<? endif ?>>Bouvet Island</option>
			<option value="BR" <? if($Info["country"] == "BR"): ?>selected<? endif ?>>Brazil</option>
			<option value="IO" <? if($Info["country"] == "IO"): ?>selected<? endif ?>>British Indian Ocean Territory</option>
			<option value="VG" <? if($Info["country"] == "VG"): ?>selected<? endif ?>>British Virgin Islands</option>
			<option value="BN" <? if($Info["country"] == "BN"): ?>selected<? endif ?>>Brunei</option>
			<option value="BG" <? if($Info["country"] == "BG"): ?>selected<? endif ?>>Bulgaria</option>
			<option value="BF" <? if($Info["country"] == "BF"): ?>selected<? endif ?>>Burkina Faso</option>
			<option value="BI" <? if($Info["country"] == "BI"): ?>selected<? endif ?>>Burundi</option>
			<option value="KH" <? if($Info["country"] == "KH"): ?>selected<? endif ?>>Cambodia</option>
			<option value="CM" <? if($Info["country"] == "CM"): ?>selected<? endif ?>>Cameroon</option>
			<option value="CA" <? if($Info["country"] == "CA"): ?>selected<? endif ?>>Canada</option>
			<option value="CV" <? if($Info["country"] == "CV"): ?>selected<? endif ?>>Cape Verde</option>
			<option value="KY" <? if($Info["country"] == "KY"): ?>selected<? endif ?>>Cayman Islands</option>
			<option value="CF" <? if($Info["country"] == "CF"): ?>selected<? endif ?>>Central African Republic</option>
			<option value="TD" <? if($Info["country"] == "TD"): ?>selected<? endif ?>>Chad</option>
			<option value="CL" <? if($Info["country"] == "CL"): ?>selected<? endif ?>>Chile</option>
			<option value="CN" <? if($Info["country"] == "CN"): ?>selected<? endif ?>>China</option>
			<option value="CX" <? if($Info["country"] == "CX"): ?>selected<? endif ?>>Christmas Island</option>
			<option value="CC" <? if($Info["country"] == "CC"): ?>selected<? endif ?>>Cocos Islands</option>
			<option value="CO" <? if($Info["country"] == "CO"): ?>selected<? endif ?>>Colombia</option>
			<option value="KM" <? if($Info["country"] == "KM"): ?>selected<? endif ?>>Comoros</option>
			<option value="CK" <? if($Info["country"] == "CK"): ?>selected<? endif ?>>Cook Islands</option>
			<option value="CR" <? if($Info["country"] == "CR"): ?>selected<? endif ?>>Costa Rica</option>
			<option value="HR" <? if($Info["country"] == "HR"): ?>selected<? endif ?>>Croatia</option>
			<option value="CU" <? if($Info["country"] == "CU"): ?>selected<? endif ?>>Cuba</option>
			<option value="CW" <? if($Info["country"] == "CW"): ?>selected<? endif ?>>Curacao</option>
			<option value="CY" <? if($Info["country"] == "CY"): ?>selected<? endif ?>>Cyprus</option>
			<option value="CZ" <? if($Info["country"] == "CZ"): ?>selected<? endif ?>>Czech Republic</option>
			<option value="CD" <? if($Info["country"] == "CD"): ?>selected<? endif ?>>DR of the Congo</option>
			<option value="DK" <? if($Info["country"] == "DK"): ?>selected<? endif ?>>Denmark</option>
			<option value="DJ" <? if($Info["country"] == "DJ"): ?>selected<? endif ?>>Djibouti</option>
			<option value="DM" <? if($Info["country"] == "DM"): ?>selected<? endif ?>>Dominica</option>
			<option value="DO" <? if($Info["country"] == "DO"): ?>selected<? endif ?>>Dominican Republic</option>
			<option value="TL" <? if($Info["country"] == "TL"): ?>selected<? endif ?>>East Timor</option>
			<option value="EC" <? if($Info["country"] == "EC"): ?>selected<? endif ?>>Ecuador</option>
			<option value="EG" <? if($Info["country"] == "EG"): ?>selected<? endif ?>>Egypt</option>
			<option value="SV" <? if($Info["country"] == "SV"): ?>selected<? endif ?>>El Salvador</option>
			<option value="GQ" <? if($Info["country"] == "GQ"): ?>selected<? endif ?>>Equatorial Guinea</option>
			<option value="ER" <? if($Info["country"] == "ER"): ?>selected<? endif ?>>Eritrea</option>
			<option value="EE" <? if($Info["country"] == "EE"): ?>selected<? endif ?>>Estonia</option>
			<option value="ET" <? if($Info["country"] == "ET"): ?>selected<? endif ?>>Ethiopia</option>
			<option value="FK" <? if($Info["country"] == "FK"): ?>selected<? endif ?>>Falkland Islands</option>
			<option value="FO" <? if($Info["country"] == "FO"): ?>selected<? endif ?>>Faroe Islands</option>
			<option value="FJ" <? if($Info["country"] == "FJ"): ?>selected<? endif ?>>Fiji</option>
			<option value="FI" <? if($Info["country"] == "FI"): ?>selected<? endif ?>>Finland</option>
			<option value="FR" <? if($Info["country"] == "FR"): ?>selected<? endif ?>>France</option>
			<option value="GF" <? if($Info["country"] == "GF"): ?>selected<? endif ?>>French Guiana</option>
			<option value="PF" <? if($Info["country"] == "PF"): ?>selected<? endif ?>>French Polynesia</option>
			<option value="TF" <? if($Info["country"] == "TF"): ?>selected<? endif ?>>French Southern Territories</option>
			<option value="GA" <? if($Info["country"] == "GA"): ?>selected<? endif ?>>Gabon</option>
			<option value="GM" <? if($Info["country"] == "GM"): ?>selected<? endif ?>>Gambia</option>
			<option value="GE" <? if($Info["country"] == "GE"): ?>selected<? endif ?>>Georgia</option>
			<option value="DE" <? if($Info["country"] == "DE"): ?>selected<? endif ?>>Germany</option>
			<option value="GH" <? if($Info["country"] == "GH"): ?>selected<? endif ?>>Ghana</option>
			<option value="GI" <? if($Info["country"] == "GI"): ?>selected<? endif ?>>Gibraltar</option>
			<option value="GR" <? if($Info["country"] == "GR"): ?>selected<? endif ?>>Greece</option>
			<option value="GL" <? if($Info["country"] == "GL"): ?>selected<? endif ?>>Greenland</option>
			<option value="GD" <? if($Info["country"] == "GD"): ?>selected<? endif ?>>Grenada</option>
			<option value="GP" <? if($Info["country"] == "GP"): ?>selected<? endif ?>>Guadeloupe</option>
			<option value="GU" <? if($Info["country"] == "GU"): ?>selected<? endif ?>>Guam</option>
			<option value="GT" <? if($Info["country"] == "GT"): ?>selected<? endif ?>>Guatemala</option>
			<option value="GG" <? if($Info["country"] == "GG"): ?>selected<? endif ?>>Guernsey</option>
			<option value="GN" <? if($Info["country"] == "GN"): ?>selected<? endif ?>>Guinea</option>
			<option value="GW" <? if($Info["country"] == "GW"): ?>selected<? endif ?>>Guinea-Bissau</option>
			<option value="GY" <? if($Info["country"] == "GY"): ?>selected<? endif ?>>Guyana</option>
			<option value="HT" <? if($Info["country"] == "HT"): ?>selected<? endif ?>>Haiti</option>
			<option value="HM" <? if($Info["country"] == "HM"): ?>selected<? endif ?>>Heard Island</option>
			<option value="HN" <? if($Info["country"] == "HN"): ?>selected<? endif ?>>Honduras</option>
			<option value="HK" <? if($Info["country"] == "HK"): ?>selected<? endif ?>>Hong Kong</option>
			<option value="HU" <? if($Info["country"] == "HU"): ?>selected<? endif ?>>Hungary</option>
			<option value="IS" <? if($Info["country"] == "IS"): ?>selected<? endif ?>>Iceland</option>
			<option value="IN" <? if($Info["country"] == "IN"): ?>selected<? endif ?>>India</option>
			<option value="ID" <? if($Info["country"] == "ID"): ?>selected<? endif ?>>Indonesia</option>
			<option value="IR" <? if($Info["country"] == "IR"): ?>selected<? endif ?>>Iran</option>
			<option value="IQ" <? if($Info["country"] == "IQ"): ?>selected<? endif ?>>Iraq</option>
			<option value="IE" <? if($Info["country"] == "IE"): ?>selected<? endif ?>>Ireland</option>
			<option value="IM" <? if($Info["country"] == "IM"): ?>selected<? endif ?>>Isle of Man</option>
			<option value="IL" <? if($Info["country"] == "IL"): ?>selected<? endif ?>>Israel</option>
			<option value="IT" <? if($Info["country"] == "IT"): ?>selected<? endif ?>>Italy</option>
			<option value="CI" <? if($Info["country"] == "CI"): ?>selected<? endif ?>>Ivory Coast</option>
			<option value="JM" <? if($Info["country"] == "JM"): ?>selected<? endif ?>>Jamaica</option>
			<option value="JP" <? if($Info["country"] == "JP"): ?>selected<? endif ?>>Japan</option>
			<option value="JE" <? if($Info["country"] == "JE"): ?>selected<? endif ?>>Jersey</option>
			<option value="JO" <? if($Info["country"] == "JO"): ?>selected<? endif ?>>Jordan</option>
			<option value="KZ" <? if($Info["country"] == "KZ"): ?>selected<? endif ?>>Kazakhstan</option>
			<option value="KE" <? if($Info["country"] == "KE"): ?>selected<? endif ?>>Kenya</option>
			<option value="KI" <? if($Info["country"] == "KI"): ?>selected<? endif ?>>Kiribati</option>
			<option value="XK" <? if($Info["country"] == "XK"): ?>selected<? endif ?>>Kosovo</option>
			<option value="KW" <? if($Info["country"] == "KW"): ?>selected<? endif ?>>Kuwait</option>
			<option value="KG" <? if($Info["country"] == "KG"): ?>selected<? endif ?>>Kyrgyzstan</option>
			<option value="LA" <? if($Info["country"] == "LA"): ?>selected<? endif ?>>Laos</option>
			<option value="LV" <? if($Info["country"] == "LV"): ?>selected<? endif ?>>Latvia</option>
			<option value="LB" <? if($Info["country"] == "LB"): ?>selected<? endif ?>>Lebanon</option>
			<option value="LS" <? if($Info["country"] == "LS"): ?>selected<? endif ?>>Lesotho</option>
			<option value="LR" <? if($Info["country"] == "LR"): ?>selected<? endif ?>>Liberia</option>
			<option value="LY" <? if($Info["country"] == "LY"): ?>selected<? endif ?>>Libya</option>
			<option value="LI" <? if($Info["country"] == "LI"): ?>selected<? endif ?>>Liechtenstein</option>
			<option value="LT" <? if($Info["country"] == "LT"): ?>selected<? endif ?>>Lithuania</option>
			<option value="LU" <? if($Info["country"] == "LU"): ?>selected<? endif ?>>Luxembourg</option>
			<option value="MO" <? if($Info["country"] == "MO"): ?>selected<? endif ?>>Macao</option>
			<option value="MK" <? if($Info["country"] == "MK"): ?>selected<? endif ?>>Macedonia</option>
			<option value="MG" <? if($Info["country"] == "MG"): ?>selected<? endif ?>>Madagascar</option>
			<option value="MW" <? if($Info["country"] == "MW"): ?>selected<? endif ?>>Malawi</option>
			<option value="MY" <? if($Info["country"] == "MY"): ?>selected<? endif ?>>Malaysia</option>
			<option value="MV" <? if($Info["country"] == "MV"): ?>selected<? endif ?>>Maldives</option>
			<option value="ML" <? if($Info["country"] == "ML"): ?>selected<? endif ?>>Mali</option>
			<option value="MT" <? if($Info["country"] == "MT"): ?>selected<? endif ?>>Malta</option>
			<option value="MH" <? if($Info["country"] == "MH"): ?>selected<? endif ?>>Marshall Islands</option>
			<option value="MQ" <? if($Info["country"] == "MQ"): ?>selected<? endif ?>>Martinique</option>
			<option value="MR" <? if($Info["country"] == "MR"): ?>selected<? endif ?>>Mauritania</option>
			<option value="MU" <? if($Info["country"] == "MU"): ?>selected<? endif ?>>Mauritius</option>
			<option value="YT" <? if($Info["country"] == "YT"): ?>selected<? endif ?>>Mayotte</option>
			<option value="MX" <? if($Info["country"] == "MX"): ?>selected<? endif ?>>Mexico</option>
			<option value="FM" <? if($Info["country"] == "FM"): ?>selected<? endif ?>>Micronesia</option>
			<option value="MD" <? if($Info["country"] == "MD"): ?>selected<? endif ?>>Moldova</option>
			<option value="MC" <? if($Info["country"] == "MC"): ?>selected<? endif ?>>Monaco</option>
			<option value="MN" <? if($Info["country"] == "MN"): ?>selected<? endif ?>>Mongolia</option>
			<option value="ME" <? if($Info["country"] == "ME"): ?>selected<? endif ?>>Montenegro</option>
			<option value="MS" <? if($Info["country"] == "MS"): ?>selected<? endif ?>>Montserrat</option>
			<option value="MA" <? if($Info["country"] == "MA"): ?>selected<? endif ?>>Morocco</option>
			<option value="MZ" <? if($Info["country"] == "MZ"): ?>selected<? endif ?>>Mozambique</option>
			<option value="MM" <? if($Info["country"] == "MM"): ?>selected<? endif ?>>Myanmar</option>
			<option value="NA" <? if($Info["country"] == "NA"): ?>selected<? endif ?>>Namibia</option>
			<option value="NR" <? if($Info["country"] == "NR"): ?>selected<? endif ?>>Nauru</option>
			<option value="NP" <? if($Info["country"] == "NP"): ?>selected<? endif ?>>Nepal</option>
			<option value="NL" <? if($Info["country"] == "NL"): ?>selected<? endif ?>>Netherlands</option>
			<option value="NC" <? if($Info["country"] == "NC"): ?>selected<? endif ?>>New Caledonia</option>
			<option value="NZ" <? if($Info["country"] == "NZ"): ?>selected<? endif ?>>New Zealand</option>
			<option value="NI" <? if($Info["country"] == "NI"): ?>selected<? endif ?>>Nicaragua</option>
			<option value="NE" <? if($Info["country"] == "NE"): ?>selected<? endif ?>>Niger</option>
			<option value="NG" <? if($Info["country"] == "NG"): ?>selected<? endif ?>>Nigeria</option>
			<option value="NU" <? if($Info["country"] == "NU"): ?>selected<? endif ?>>Niue</option>
			<option value="NF" <? if($Info["country"] == "NF"): ?>selected<? endif ?>>Norfolk Island</option>
			<option value="KP" <? if($Info["country"] == "KP"): ?>selected<? endif ?>>North Korea</option>
			<option value="MP" <? if($Info["country"] == "MP"): ?>selected<? endif ?>>Northern Mariana Islands</option>
			<option value="NO" <? if($Info["country"] == "NO"): ?>selected<? endif ?>>Norway</option>
			<option value="OM" <? if($Info["country"] == "OM"): ?>selected<? endif ?>>Oman</option>
			<option value="PK" <? if($Info["country"] == "PK"): ?>selected<? endif ?>>Pakistan</option>
			<option value="PW" <? if($Info["country"] == "PW"): ?>selected<? endif ?>>Palau</option>
			<option value="PS" <? if($Info["country"] == "PS"): ?>selected<? endif ?>>Palestinian Territory</option>
			<option value="PA" <? if($Info["country"] == "PA"): ?>selected<? endif ?>>Panama</option>
			<option value="PG" <? if($Info["country"] == "PG"): ?>selected<? endif ?>>Papua New Guinea</option>
			<option value="PY" <? if($Info["country"] == "PY"): ?>selected<? endif ?>>Paraguay</option>
			<option value="PE" <? if($Info["country"] == "PE"): ?>selected<? endif ?>>Peru</option>
			<option value="PH" <? if($Info["country"] == "PH"): ?>selected<? endif ?>>Philippines</option>
			<option value="PN" <? if($Info["country"] == "PN"): ?>selected<? endif ?>>Pitcairn</option>
			<option value="PL" <? if($Info["country"] == "PL"): ?>selected<? endif ?>>Poland</option>
			<option value="PT" <? if($Info["country"] == "PT"): ?>selected<? endif ?>>Portugal</option>
			<option value="PR" <? if($Info["country"] == "PR"): ?>selected<? endif ?>>Puerto Rico</option>
			<option value="QA" <? if($Info["country"] == "QA"): ?>selected<? endif ?>>Qatar</option>
			<option value="CG" <? if($Info["country"] == "CG"): ?>selected<? endif ?>>Republic of the Congo</option>
			<option value="RE" <? if($Info["country"] == "RE"): ?>selected<? endif ?>>Reunion</option>
			<option value="RO" <? if($Info["country"] == "RO"): ?>selected<? endif ?>>Romania</option>
			<option value="RU" <? if($Info["country"] == "RU"): ?>selected<? endif ?>>Russia</option>
			<option value="RW" <? if($Info["country"] == "RW"): ?>selected<? endif ?>>Rwanda</option>
			<option value="BL" <? if($Info["country"] == "BL"): ?>selected<? endif ?>>Saint Barthelemy</option>
			<option value="SH" <? if($Info["country"] == "SH"): ?>selected<? endif ?>>Saint Helena</option>
			<option value="KN" <? if($Info["country"] == "KN"): ?>selected<? endif ?>>Saint Kitts and Nevis</option>
			<option value="LC" <? if($Info["country"] == "LC"): ?>selected<? endif ?>>Saint Lucia</option>
			<option value="MF" <? if($Info["country"] == "MF"): ?>selected<? endif ?>>Saint Martin</option>
			<option value="PM" <? if($Info["country"] == "PM"): ?>selected<? endif ?>>Saint Pierre and Miquelon</option>
			<option value="VC" <? if($Info["country"] == "VC"): ?>selected<? endif ?>>Saint Vincent</option>
			<option value="WS" <? if($Info["country"] == "WS"): ?>selected<? endif ?>>Samoa</option>
			<option value="SM" <? if($Info["country"] == "SM"): ?>selected<? endif ?>>San Marino</option>
			<option value="ST" <? if($Info["country"] == "ST"): ?>selected<? endif ?>>Sao Tome and Principe</option>
			<option value="SA" <? if($Info["country"] == "SA"): ?>selected<? endif ?>>Saudi Arabia</option>
			<option value="SN" <? if($Info["country"] == "SN"): ?>selected<? endif ?>>Senegal</option>
			<option value="RS" <? if($Info["country"] == "RS"): ?>selected<? endif ?>>Serbia</option>
			<option value="SC" <? if($Info["country"] == "SC"): ?>selected<? endif ?>>Seychelles</option>
			<option value="SL" <? if($Info["country"] == "SL"): ?>selected<? endif ?>>Sierra Leone</option>
			<option value="SG" <? if($Info["country"] == "SG"): ?>selected<? endif ?>>Singapore</option>
			<option value="SX" <? if($Info["country"] == "SX"): ?>selected<? endif ?>>Sint Maarten</option>
			<option value="SK" <? if($Info["country"] == "SK"): ?>selected<? endif ?>>Slovakia</option>
			<option value="SI" <? if($Info["country"] == "SI"): ?>selected<? endif ?>>Slovenia</option>
			<option value="SB" <? if($Info["country"] == "SB"): ?>selected<? endif ?>>Solomon Islands</option>
			<option value="SO" <? if($Info["country"] == "SO"): ?>selected<? endif ?>>Somalia</option>
			<option value="ZA" <? if($Info["country"] == "ZA"): ?>selected<? endif ?>>South Africa</option>
			<option value="GS" <? if($Info["country"] == "GS"): ?>selected<? endif ?>>South Georgia</option>
			<option value="KR" <? if($Info["country"] == "KR"): ?>selected<? endif ?>>South Korea</option>
			<option value="SS" <? if($Info["country"] == "SS"): ?>selected<? endif ?>>South Sudan</option>
			<option value="ES" <? if($Info["country"] == "ES"): ?>selected<? endif ?>>Spain</option>
			<option value="LK" <? if($Info["country"] == "LK"): ?>selected<? endif ?>>Sri Lanka</option>
			<option value="SD" <? if($Info["country"] == "SD"): ?>selected<? endif ?>>Sudan</option>
			<option value="SR" <? if($Info["country"] == "SR"): ?>selected<? endif ?>>Suriname</option>
			<option value="SJ" <? if($Info["country"] == "SJ"): ?>selected<? endif ?>>Svalbard and Jan Mayen</option>
			<option value="SZ" <? if($Info["country"] == "SZ"): ?>selected<? endif ?>>Swaziland</option>
			<option value="SE" <? if($Info["country"] == "SE"): ?>selected<? endif ?>>Sweden</option>
			<option value="CH" <? if($Info["country"] == "CH"): ?>selected<? endif ?>>Switzerland</option>
			<option value="SY" <? if($Info["country"] == "SY"): ?>selected<? endif ?>>Syria</option>
			<option value="TW" <? if($Info["country"] == "TW"): ?>selected<? endif ?>>Taiwan</option>
			<option value="TJ" <? if($Info["country"] == "TJ"): ?>selected<? endif ?>>Tajikistan</option>
			<option value="TZ" <? if($Info["country"] == "TZ"): ?>selected<? endif ?>>Tanzania</option>
			<option value="TH" <? if($Info["country"] == "TH"): ?>selected<? endif ?>>Thailand</option>
			<option value="TG" <? if($Info["country"] == "TG"): ?>selected<? endif ?>>Togo</option>
			<option value="TK" <? if($Info["country"] == "TK"): ?>selected<? endif ?>>Tokelau</option>
			<option value="TO" <? if($Info["country"] == "TO"): ?>selected<? endif ?>>Tonga</option>
			<option value="TT" <? if($Info["country"] == "TT"): ?>selected<? endif ?>>Trinidad and Tobago</option>
			<option value="TN" <? if($Info["country"] == "TN"): ?>selected<? endif ?>>Tunisia</option>
			<option value="TR" <? if($Info["country"] == "TR"): ?>selected<? endif ?>>Turkey</option>
			<option value="TM" <? if($Info["country"] == "TM"): ?>selected<? endif ?>>Turkmenistan</option>
			<option value="TC" <? if($Info["country"] == "TC"): ?>selected<? endif ?>>Turks and Caicos Islands</option>
			<option value="TV" <? if($Info["country"] == "TV"): ?>selected<? endif ?>>Tuvalu</option>
			<option value="VI" <? if($Info["country"] == "VI"): ?>selected<? endif ?>>U.S. Virgin Islands</option>
			<option value="UG" <? if($Info["country"] == "UG"): ?>selected<? endif ?>>Uganda</option>
			<option value="UA" <? if($Info["country"] == "UA"): ?>selected<? endif ?>>Ukraine</option>
			<option value="AE" <? if($Info["country"] == "AE"): ?>selected<? endif ?>>United Arab Emirates</option>
			<option value="GB" <? if($Info["country"] == "GB"): ?>selected<? endif ?>>United Kingdom</option>
			<option value="US" <? if($Info["country"] == "US"): ?>selected<? endif ?>>United States</option>
			<option value="UY" <? if($Info["country"] == "UY"): ?>selected<? endif ?>>Uruguay</option>
			<option value="UZ" <? if($Info["country"] == "UZ"): ?>selected<? endif ?>>Uzbekistan</option>
			<option value="VU" <? if($Info["country"] == "VU"): ?>selected<? endif ?>>Vanuatu</option>
			<option value="VA" <? if($Info["country"] == "VA"): ?>selected<? endif ?>>Vatican</option>
			<option value="VE" <? if($Info["country"] == "VE"): ?>selected<? endif ?>>Venezuela</option>
			<option value="VN" <? if($Info["country"] == "VN"): ?>selected<? endif ?>>Vietnam</option>
			<option value="WF" <? if($Info["country"] == "WF"): ?>selected<? endif ?>>Wallis and Futuna</option>
			<option value="EH" <? if($Info["country"] == "EH"): ?>selected<? endif ?>>Western Sahara</option>
			<option value="YE" <? if($Info["country"] == "YE"): ?>selected<? endif ?>>Yemen</option>
			<option value="ZM" <? if($Info["country"] == "ZM"): ?>selected<? endif ?>>Zambia</option>
			<option value="ZW" <? if($Info["country"] == "ZW"): ?>selected<? endif ?>>Zimbabwe</option>
		</select>
		<br><br>
		<label for="st_in" style="display:block;font-size:13px;position:relative;bottom:1px">Interests:</label>
		<input type="text" id="st_in" name="interests" value="<?= htmlspecialchars($Info["i_interests"]) ?>" maxlength="128" size="36"><br><br>
		<label for="st_mu" style="display:block;font-size:13px;position:relative;bottom:1px">Movies:</label>
		<input type="text" id="st_mu" name="movies" value="<?= htmlspecialchars($Info["i_movies"]) ?>" maxlength="128" size="36"><br><br>
		<label for="st_mzb" style="display:block;font-size:13px;position:relative;bottom:1px">Music:</label>
		<input type="text" id="st_mzb" name="music" value="<?= htmlspecialchars($Info["i_music"]) ?>" maxlength="128" size="36"><br><br>
		<label for="boo" style="display:block;font-size:13px;position:relative;bottom:1px">Books:</label>
		<input type="text" id="boo" name="books" value="<?= htmlspecialchars($Info["i_books"]) ?>" maxlength="128" size="36">
	</div>
	<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
		<img src="/img/clp00.png">
		<span class="u_sct_hd">Education / Career</span>
	</div>
	<div style="display:none;position:relative;left:6.5px">
		<label for="st_sh" style="display:block;font-size:13px;position:relative;bottom:1px">Schools:</label>
		<input type="text" id="st_sh" name="schools" value="<?= htmlspecialchars($Info["i_schools"]) ?>" maxlength="128" size="36"><br><br>
		<label for="st_bo" style="display:block;font-size:13px;position:relative;bottom:1px">Occupation:</label>
		<input type="text" id="st_bo" name="occupation" value="<?= htmlspecialchars($Info["i_occupation"]) ?>" maxlength="128" size="36">
	</div>
	<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
		<img src="/img/clp00.png">
		<span class="u_sct_hd">Visibility Options</span>
	</div>
	<div style="display:none;position:relative;left:6.5px">
		<div><label><input type="checkbox" name="show_age" <?= $Info["a_age"] ? 'checked=""' : "" ?>/> Show age</label></div>
		<div><label><input type="checkbox" name="show_country" <?= $Info["a_country"] ? 'checked=""' : "" ?>/> Show country</label></div>
		<div><label><input type="checkbox" name="show_signin" <?= $Info["a_last"] ? 'checked=""' : "" ?>/> Show last sign in</label></div>
	</div>
	<div style="margin-top:25px">
		<input class="search_button" type="submit" name="update_info" value="Save Changes">
	</div>
</form>
<? endif ?>