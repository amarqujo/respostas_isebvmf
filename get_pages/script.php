<?php
/*****************************************************************
Created By : Joaquim Marques (Joaquim.A.Marques@sapo.pt)
*****************************************************************/

include('../apps/funcs.php');

@unlink('list.txt');
$outlist = fopen('list.txt','a');

$fin = file('in.txt');
for($i=0;$i<sizeof($fin);$i++){

	$count = 0;

	$url  = trim($fin[$i]);
	$year = substr($url,strrpos($url,'/')+1);

	if(!is_dir('./'.$year)) mkdir('./'.$year);
	$indir=opendir('./'.$year);
	while ($file = readdir($indir)){
		if(strstr($file, '.html') == TRUE){
			unlink('./'.$year.'/'.$file);
		}
	}

	shell_exec('"%CD%\..\apps\phantomjs" "%CD%\..\apps\phantomjs.txt" "'.$url.'" > ./'.$year.'/all_'.$year.'.html');

	$fget1 = shell_exec('"%CD%\..\apps\curl" -A "'.$agent.'" -s --compressed -k -L "'.$url.'"');
	$fget1 = str_replace(array('<div class="box02">','<td class="tit_holding">','<td class="tit_controlada">'),array("\n".'<div class="box02">',"\n".'<td class="tit_holding">',"\n".'<td class="tit_controlada">'),str_clean($fget1));
	$fget1 = explode("\n",$fget1);

	for($k=0;$k<sizeof($fget1);$k++){

		$linek = $fget1[$k];

		if(strstr($linek, '<td class="tit_holding">') || strstr($linek, '<td class="tit_controlada">')){

			$count++;
			$comp = explode('#@#',str_replace('<td class="center"','#@#<td class="center"',$linek));

			fwrite($outlist, $year.';'.$count.';'.strp_tags($comp[0]).$eol);

			echo $eol.$year.' - '.$count.' - '.strp_tags($comp[0]);

			$compname = $count; 

			for($l=1;$l<8;$l++){

				if(strstr($comp[$l], 'href=')){

					$url1 = 'http://respostas.isebvmf.com.br'.get_str('href="','"',$comp[$l]);
					$fget2 = shell_exec('"%CD%\..\apps\curl" -A "'.$agent.'" -s --compressed -k -L "'.$url1.'"');
					$url2 = get_str("<iframe src='","'",$fget2);

					$loop = 1;
					while($loop > 0){
					$tgfile = './'.$year.'/'.$compname.'_'.$l.'.html';

					shell_exec('"%CD%\..\apps\phantomjs" "%CD%\..\apps\phantomjs.txt" "'.$url2.'" > '.$tgfile);

					$nf = './'.$year.'/'.$compname.'_'.$l.'_resp.html';

					$getf = 'https:'.get_str('<script src="https:','"',file_get_contents($tgfile));
					shell_exec('"%CD%\..\apps\curl" -A "'.$agent.'" -s --compressed -k -L -o '.$nf.'  "'.$getf.'"');

					if(strstr(file_get_contents($tgfile), '<h1 class="categoria" name="categoria"')) $loop = 0;

					} // while($loop > 0){

				}
				else
				{
					copy('../apps/utf8.txt','./'.$year.'/'.$compname.'_'.$l.'.html');
				}

			}

		}

	}

}


?>