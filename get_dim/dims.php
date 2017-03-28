<?php
/*****************************************************************
Created By : Joaquim Marques (Joaquim.A.Marques@sapo.pt)
*****************************************************************/

include('../apps/funcs.php');

if(!is_dir('./out/')) mkdir('./out/');

$grp = array('','Geral','Natureza do Produto','Governança Corporativa','Econômico - Financeira','Ambiental','Social','Mudanças Climáticas');

$bcomp = file('../get_pages/list.txt');
foreach($bcomp as $bcompel){
	$line = explode(';',trim($bcompel));
	if($line[0] == '2014') $arr['2014'][] = $line[2];
	if($line[0] == '2016') $arr['2016'][] = $line[2];
}

$yarr = array('2014','2016');

foreach($yarr as $year){

	@unlink('./out/'.$year.'_dim.csv');
	copy('../apps/utf8.txt','./out/'.$year.'_dim.csv');
	$out2 = fopen('./out/'.$year.'_dim.csv','a');

	$fin = file('./out/'.$year.'_textos.csv');

	for($k=1;$k<8;$k++){

		for($i=0;$i<sizeof($fin);$i++){

			$line = explode(';',trim($fin[$i]));

			if($line[0] == $k && $line[5] != ''){

				$cnt = 1;
				$cnt1 = $line[1];

				fwrite($out2, trim($fin[$i]).$eol);

				foreach($arr[$year] as $arrelem){

					fwrite($out2, $line[0].';'.$line[1].';'.$line[2].';'.$line[3].';'.$line[4].';'.$line[5].';'.$arrelem);

					$arrresp1 = '';

					$fhtml = get_str('<ol id="questionnaire">','</form>',file_get_contents('../get_pages/'.$year.'/'.$cnt.'_'.$k.'.html'));					
					if(strstr($fhtml, '>'.$line[5].'<')){

						$sresp = get_str('>'.$line[5].'<','<ol ',$fhtml);
						$table = 0;
						if(strstr($sresp, '<tbody>')) $table = 1;

						if(is_file("../get_pages/".$year."/".$cnt.'_'.$k.'_resp.html')){

							$arrresp = '';
							$fresp = file_get_contents("../get_pages/".$year."/".$cnt.'_'.$k.'_resp.html');
							$fresp = get_str('fn.aplicar_respostas(',')',$fresp);
							$fct1 = json_decode($fresp);

							if(!is_object($fct1)) $fct1 = array_values($fct1);

							if(is_object($fct1)){
								$fct1 = json_decode($fresp, true);
							}

							if($table == 0){
								if(is_array($fct1[$cnt1]))  $arrresp = ','.implode(',',$fct1[$cnt1]).',';
								if(!is_array($fct1[$cnt1])) $arrresp = ','.$fct1[$cnt1].',';
							}

							if($table == 1){

								if(!is_array($fct1[$cnt1])) $arrresp = ','.$fct1[$cnt1].',';

								if(!is_array($fct1[$cnt1][0]) && is_array($fct1[$cnt1])){
									foreach($fct1[$cnt1] as $fct1el1) $arrresp .= ','.$fct1el1.',#';
								}

								if(is_array($fct1[$cnt1][0])){
									foreach($fct1[$cnt1] as $fct1el1) $arrresp .= ','.implode(',',$fct1el1).',#';
									$arrresp = str_replace('Array','',$arrresp);
									$arrresp = trim(str_replace(',,','',$arrresp));
								}

							}

							$arrresp = trim(str_replace(',,','',$arrresp));
							$arrresp1 = '';

							if(!stristr($arrresp, 'array') && $arrresp != '' && !stristr($arrresp, '#')){
								$max = max(explode(',',$arrresp));

								for($x=0;$x<($max+1);$x++){
									if(strstr($arrresp, ','.$x.',')){$arrresp1 .= ';X';}else{$arrresp1 .= ';';}
								}

							}

							if(stristr($arrresp, '#')){
								$section = get_str('>'.$line[5].'<','</tbody>',$fhtml);
								$nropt = get_str('<tbody>','</tbody>',$section.'</tbody>');
								$nropt = substr_count(get_str('<tr>','</tr>',$nropt), '<td');

								$respg = explode('#',$arrresp);
								foreach($respg as $respgel){

									for($j=0;$j<($nropt-1);$j++){
										if(strstr($respgel, ','.$j.',')){$arrresp1 .= ';X';}else{$arrresp1 .= ';';}
									}

								}
							}

						}

					}

					fwrite($out2, $arrresp1.$eol);

					$cnt++;
				}
			}
		}
	}
}


?>