<?php
echo base64_encode(file_get_contents("https://www.qrcoder.co.uk/api/v3/?key=p6Ly8nbZvmhAwESfYNaQTe2PCGFiKtU4&text=".urlencode($_GET["name"])));