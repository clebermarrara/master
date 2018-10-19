<?php

	$rs2 = new recordset();
	$fn = new functions();
	
	$dms = array(1=>31, 2=>28, 3=>31, 4=>30, 5=>31, 6=>30, 7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
	if(date("d") <= $dms[date("m")]){$dia = 31 - $dms[date("m")];}
	else{$dia = date("d");}
	$df = $dia+2;
	$sql = "SELECT * FROM obrigacoes a
					JOIN tipos_impostos b ON a.ob_titulo = b.imp_id
						WHERE ob_venc >= ".date('d')."
						AND ob_venc <= {$df}
						AND imp_depto=".$_SESSION['dep']." 
						AND ob_ativo = 1
						GROUP BY imp_id 
						ORDER BY ob_venc ASC";
	$rs->FreeSql($sql);
	//echo $sql;
	?>
<table class="table table-striped table-condensed" id="empr">
	<tr>
		<th width="200px">Imposto</th>
		<th width="150px">Vencimento <small>(esse m&ecirc;s)</small></th>
		<th>Empresas</th>
	</tr>
	<?php 
		//echo $rs->sql;
		if($rs->linhas==0):
			echo "<tr><td colspan=7> Nenhum dado</td></tr>";
			else:
				while($rs->GeraDados()){
				?>
				<tr>
					<td><?="[".$rs->fld("imp_tipo")."]".$rs->fld("imp_nome");?></td>
					<td class="hidden-xs">
						<?php
							/*
							if($rs->fld("imp_regra")=="mes_subs"){
								$mes = $rs->fld("ob_venc") - 1;
								$ref =  date("m/Y", strtotime("+".$mes." month"));
								echo $fn->ultimo_dia_mes($ref);
							}
							else{
								echo $fn->data_br($fn->dia_util($rs->fld("ob_venc"),$rs->fld("imp_regra")));
							}
							*/
							if($rs->fld("imp_regra")=="mes_subs"){
								$cc=substr($cc,0,2)+1;
								$mes = (is_null($rs->fld("imp_mes"))?$cc:$rs->fld("imp_mes")-1);
								$ref = date("m/Y", strtotime("+".$mes." month"));
								$ref2 = ((isset($comp) AND $comp<>"")?$cc+$mes:date("m")+$mes);
								$vaj = $rs->fld("ob_venc"); 
								$vn = (($rs->fld("ob_venc")<>"" AND $rs->fld("ob_venc")<>0)?$fn->data_br($fn->dia_util($vaj,"dia_util",$ref2)):$fn->ultimo_dia_mes($ref));
							}
							else{
								
								$vn =  $fn->data_br($fn->dia_util($rs->fld("ob_venc"),$rs->fld("imp_regra"),$cc));
								

							}
							echo $vn;
						?>
					</td>
					<td class="hidden-xs">
					<select class="venc_prox" multiple="multiple" style="width:100%;">
					<?php
					//$tabela = ($rs->fld("imp_tipo")=="T"?"tributos":"obrigacoes");
					//$campo = ($rs->fld("imp_tipo")=="T"?"tr_":"ob_");
					$sql = "SELECT ob_cod, b.cod, b.apelido FROM obrigacoes a
								JOIN tri_clientes b ON a.ob_cod = b.cod
								WHERE ob_titulo"."=".$rs->fld("imp_id") ."
								AND ob_ativo = 1
								AND a.ob_cod NOT IN( SELECT env_codEmp FROM impostos_enviados a WHERE a.env_compet='".date("m/Y",strtotime("-1 month"))."' AND a.env_codImp = ".$rs->fld("imp_id")." AND a.env_codEmp = ob_cod )
								AND b.ativo = 1
								AND a.ob_venc = ".$rs->fld("ob_venc")."
								AND ob_depto = ".$_SESSION['dep']."
								AND b.carteira LIKE '%\"".$_SESSION['dep']."\":{\"user\":";
					if($_SESSION['lider']=="N"){ $sql.= "\"".$_SESSION['usu_cod']."\""; }
					$sql.= "%' ORDER BY cod ASC";
					//echo $sql;
					$rs2->FreeSql($sql);
					//echo $rs2->sql;
					if($rs2->linhas==0){
						echo '<option SELECTED>NENHUMA EMPRESA</option>';
					}	
					else{
						$empresas = array();
						while($rs2->GeraDados()){
							$empresas[] = "<option SELECTED>".str_pad($rs2->fld("cod"),3,"000",STR_PAD_LEFT)." - ".$rs2->fld("apelido")."</option>";
						}
						echo implode("", $empresas);
					}
					?>
					</select>
					</td>
				</tr>
			<?php  
			//echo $sql;
			}
			echo "<tr><td colspan=7><strong>".$rs->linhas." Tributo(s) / Obriga&ccedil;&atilde;o(&ccedil;&otilde;es)</strong></td></tr>";
		endif;		
	?>
</table>
	