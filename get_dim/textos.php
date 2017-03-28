<?php
/*****************************************************************
Created By : Joaquim Marques (Joaquim.A.Marques@sapo.pt)
*****************************************************************/

include('../apps/funcs.php');

if(!is_dir('./out/')) mkdir('./out/');

//some vars
$grp = array('','Geral','Natureza do Produto','Governança Corporativa','Econômico - Financeira','Ambiental','Social','Mudanças Climáticas');
$yarr = array('2014','2016');

foreach($yarr as $year){

@unlink('./out/'.$year.'_textos.html');
//$out1 = fopen('./out/'.$year.'_textos.html','a');

@unlink('./out/'.$year.'_textos.csv');
copy('../apps/utf8.txt','./out/'.$year.'_textos.csv');
$out2 = fopen('./out/'.$year.'_textos.csv','a');
fwrite($out2, 'grupo;pn;categoria;criterio;indicador;questao;texto'.$eol);

$chkstr = '';

for($k=1;$k<sizeof($grp);$k++){

	$dir = "../get_pages/".$year."/";
	$indir=opendir($dir);
	while ($file = readdir($indir)){

		if(strstr($file, '.html') && !strstr($file, '_resp.html') && strstr($file, '_'.$k.'.html')){

			$categoria = utf8_encode($grp[$k]);

			$fct1 = file_get_contents($dir.$file);
			$fct1 = get_str('<ol id="questionnaire">','</form>',$fct1);
			$fct1 = str_replace("\n",' ',$fct1);
			$fct1 = str_ed($fct1);
			$fct1 = str_replace('Â','',$fct1);
			$fct1 = str_replace('<a href="#list',$eol.'<a href="#list',$fct1);
			$fct1 = str_replace('<ol ',$eol.'<ol ',$fct1);
			$fct1 = str_replace('<div ',$eol.'<div ',$fct1);
			$fct1 = str_replace('<table class="choices"',$eol.'<table class="choices"',$fct1);
			$fct1 = str_replace('<tr',$eol.'<tr',$fct1);
			$fct1 = str_replace('<thead',$eol.'<thead',$fct1);
			$fct1 = str_replace('<tbody',$eol.'<tbody',$fct1);
			$fct1 = str_replace('<li level=',$eol.'<li level=',$fct1);

			//fwrite($out1, $fct1);

			$fct2 = $fct1;

			$fct1 = explode("\n",$fct1);

			$criterio = ''; $indicador = ''; $questao = ''; $texto = '';

			for($i=0;$i<sizeof($fct1);$i++){

				$linei = $fct1[$i];
				$lineb = $fct1[$i-2];

				if(strstr($linei, '<div class="number_list"></div><h1 class="categoria" name="categoria"') && !strstr($linei, '<hr class="sub_categoria">')){
					$criterio = trim(str_replace(array('.','–'),array('',''),get_str('block;">','<span>',$linei)));
					$criterio = trim(str_replace(array('â€“','-'),array('',''),$criterio));
					$texto = trim(str_replace(array('.','–'),array('',''),get_str('<span>','</span>',$linei)));
					if(!strstr($chkstr, '#'.$categoria.';'.$criterio.'#')) fwrite($out2, $k.';;'.$categoria.';'.$criterio.';;;'.$texto.$eol);
					$chkstr .= '#'.$categoria.';'.$criterio.'#';
				}

				if(strstr($linei, '<div class="number_list"></div><h1 class="categoria" name="categoria"') && strstr($linei, '<hr class="sub_categoria">')){
					$indicador = trim(str_replace(array('.','–'),array('',''),get_str('block;">','<span>',$linei)));
					$indicador = trim(strp_tags($indicador));
					$texto = trim(str_replace(array('.','–'),array('',''),get_str('<span>','</span>',$linei)));
					if(!strstr($chkstr, '#'.$categoria.';'.$criterio.';'.$indicador.'#')) fwrite($out2, $k.';;'.$categoria.';'.$criterio.';'.$indicador.';;'.$texto.$eol);
					$chkstr .= '#'.$categoria.';'.$criterio.';'.$indicador.'#';
				}

				if(strstr($linei, '<h2 class="nome" name="nome" style="display: block;">')){
					$questao = trim(get_str('<div class="number_list">','<',$linei));
					$texto = trim(strp_tags(get_str('<h2 class="nome" name="nome" style="display: block;">','</h2>',$linei)));

					$resp = get_str($fct1[$i+2],'<ol ',$fct2);

					$perg = ''; $table = 0;
					if(strstr($resp, '<thead>')) $table = 1;

					if($table == 1){
						$head = explode('#@#',str_replace(';',':',substr(strp_tags(trim(str_replace('</th>','#@#</th>',get_str('<thead>','</thead>',$resp)))),3,-3)));

						$resp = explode("\n",$resp);

						foreach($resp as $respnr){

							if(strstr($respnr, '<td class="text-col">')){
								$pn = get_str('name="grp[',']',$respnr);
								$opt = get_str('(',')','('.strp_tags($respnr));
								foreach($head as $headl) $perg .= ';'.$opt.') '.$headl;
							}

						}

						if(!strstr($chkstr, '#'.$categoria.';'.$criterio.';'.$indicador.';'.$questao.'#')) fwrite($out2, $k.';'.$pn.';'.$categoria.';'.$criterio.';'.$indicador.';'.$questao.';'.str_replace(';',':',$texto).$perg.$eol);
						$chkstr .= '#'.$categoria.';'.$criterio.';'.$indicador.';'.$questao.'#';
					}

					if($table == 0){
						$resp = explode("\n",$resp);

						foreach($resp as $respnr){
							$pn = get_str('name="grp[',']',$respnr);
							$perg .= ';'.get_str('(',')','('.strp_tags($respnr));
						}

						if(!strstr($chkstr, '#'.$categoria.';'.$criterio.';'.$indicador.';'.$questao.'#')) fwrite($out2, $k.';'.$pn.';'.$categoria.';'.$criterio.';'.$indicador.';'.$questao.';'.str_replace(';',':',$texto).$perg.$eol);
						$chkstr .= '#'.$categoria.';'.$criterio.';'.$indicador.';'.$questao.'#';
					}

				}

			}

		}

	}

}

}



?>