<?php
for ($i=1; $i < 8; $i++) 
{ 
	$html = file_get_contents('http://www.bangladeshpost.gov.bd/PostCodeList.asp?DivID='.$i);
	preg_match_all('/SubOffice.*\n\s+([A-Za-z]+)/', $html, $d);
	preg_match_all('/<td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">(.*)<\/font><\/div><\/td>/', $html, $match);
	$datas = array_chunk($match[1],4);
	foreach ($datas as $key => $data) 
	{
		$x['division'] = $d[1][0];
		$x['district'] = $data[0];
		$x['thana'] = $data[1];
		$x['suboffice'] = $data[2];
		$x['postcode'] = $data[3];
		$postCode[] = $x;
	}
}

var_dump(count($postCode));

function makeCSV($datas)
{
	foreach ($datas as $key => $data) 
	{
		$x[] = implode(",", $data);
	}
	return implode("\n", $x);
}
file_put_contents('postcode.csv',makeCSV($postCode));
?>