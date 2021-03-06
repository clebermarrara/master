<?php
//sujeira embaixo do tapete :(
error_reporting(E_ALL & E_NOTICE & E_WARNING);

session_start("portal");
/*inclusão dos principais itens da página */
$sec = "Rels";
$pag = "grafprod.php";
require_once("../config/main.php");
require_once("../config/valida.php");
require_once("../config/mnutop.php");
require_once("../config/menu.php");
require_once("../config/modals.php");
require_once("../class/class.functions.php");
require_once("../class/class.irpf.php");
require_once("../class/class.permissoes.php");

$per = new permissoes();

?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
          <section class="content-header">
			<h1>
				Follow-Up
				<small>Produtividade</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Follow-Up</li>
			</ol>
				
        </section>
		
		<!--|ESPAÇO GRÁFICOS PRODUTIVIDADE|-->
		<section class="content">
		<?php
			$con = $per->getPermissao("graficos",$_SESSION["usu_cod"]);
			if($con['C']==1): // Verifica permissão para ver os graficos de produtividade ?>
					<div id="view_grafs">		
						<?php
							require_once("view_grafprod.php");
						?>
					</div><!--/row	-->
				
			<?php 
			endif; 
	
			$con = $per->getPermissao("progress_chart",$_SESSION["usu_cod"]);
			if($con['C']==1): // Verifica permissão para ver os pendentes de envio ?>
					<div id="view_progress">		
						<?php
							require_once("view_grafprogress.php");
						?>
					</div>
				
			<?php 
			endif; 

			$con = $per->getPermissao("monthly",$_SESSION["usu_cod"]);
			if($con['C']==1): // Verifica permissão para ver os pendentes de envio ?>
					<div id="view_monthly">		
						<?php
							require_once("view_grafmensal.php");
						?>
					</div><!--/row	-->
			<?php 
			endif;

			$con = $per->getPermissao("area_chart",$_SESSION["usu_cod"]);
			if($con['C']==1): // Verifica permissão para ver os pendentes de envio ?>
					<div id="view_area">		
						<?php
							require_once("view_grafarea.php");
						?>
					</div><!--/row	-->
			<?php 
			endif;

		?>
		</section>
		<!--|FIM ESPAÇO GRÁFICOS PRODUTIVIDADE|-->

		<?php 
		$con = $per->getPermissao("graf_irpf",$_SESSION["usu_cod"]);
		if($con['C']==1 ):?>
    		<section class="content" id="linha_do_tempo">
				<div class="row">	
					<div class="col-md-4">
						<div class="box box-danger">
			                <div class="box-header with-border">
			                  <h3 class="box-title">IRPF <?=date("Y")-1;?></h3>
			                  <div class="box-tools pull-right">
			                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			                  </div>
			                </div>
			                <div class="box-body">
			                    <canvas id="pieChart" style="height:250px"></canvas>
			                   		<?php
					                    $irpf = new irpf();
					                    //print_r($irpf->status_ir());
					       				$status = $irpf->status_ir($sql);
					                    $cores = array(0=>"#f56954",1=>"#00a65a",2=>"#00c0ef",3=>"#3c8dbc",4=>"#f39c12",5=>"#d2d6de",6=>"#940000");
					                    $color = array(0=>"#f56954",1=>"#00a65a",2=>"#00c0ef",3=>"#3c8dbc",4=>"#f39c12",5=>"#d2d6de",6=>"#940000");
					                    $table="";
					                    $bts = "";
								        for($i=0;$i<sizeof($status); $i++){ 
								          $table .= '
								          {
								            value: '.$status[$i]["valor"].',
								            color: "'.$cores[$i].'",
								            highlight: "'.$cores[$i].'",
								            label: "'.$status[$i]["desc"].'"
								          },
								          ';
								          $bts .= "
								          	<div class='col-md-4'>
								          		<div style='width:10px; height:10px; background-color:".$cores[$i].";'>
								          		</div>".$status[$i]['desc']."
								          	</div>";
								    	}
								    	$jparam = substr($table,0,-2);
								    	echo $bts;
				                    ?>
				            </div><!-- /.box-body -->
		            	</div><!-- /.box -->
		            </div><!-- ./md-6 -->

		            <div class="col-md-4">
		            	<!-- DONUT CHART -->
			            <div class="box box-info">
			                <div class="box-header with-border">
			                	<h3 class="box-title">Pagos x N&atilde;o Pagos</h3>
			                  	<div class="box-tools pull-right">
			                    	<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                    	<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			                	</div>
			                </div>
			                <div class="box-body chart-responsive">
			                	<?php
			                		$pagos = $irpf->pagos_ir();
			                		$devem = $irpf->devem_ir();
			                		$sem_r = $irpf->sem_boleto();
			                		$dado_pagos = ' {label: "Pagos", value: '.$pagos.'},
					            					{label: "Nao Pagos", value: '.$devem.'},
					            					{label: "Sem Recibo", value: '.$sem_r.'}';

			                	?>
			                 	<div class="chart" id="irpf_pagos" style="height: 300px; position: relative;"></div>
			                </div><!-- /.box-body -->
			            </div><!-- /.box -->
		            </div>


		            <div class="col-md-4 bg-white">
			              <div class="box box-success">
			                <div class="box-header with-border">
			                  <h3 class="box-title">Ano Passado x Atual</h3>
			                  <div class="box-tools pull-right">
			                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			                  </div>
			                </div>
			                <div class="box-body chart-responsive">
			                
				                <?php
				                	//print_r($irpf->history_ir());
				                	$historico = $irpf->history_ir();
				                	$data="";
				                    //$bts = "";
							        for($i=0;$i<sizeof($historico); $i++){ 
							          $data .= "
			                			{y: '".$historico[$i]["ano"]."', a: ".$historico[$i]["valor"]."},
							          ";
							          $datahis = substr($data,0,-2);
							        }
							        //echo $datahis;
				                ?>
				              <div class="chart" id="bar-chart" style="height: 300px;"></div>
			                </div><!-- /.box-body -->
			            </div><!-- /.box -->
		            </div>
				</div><!--./row-->
			</section>
		<?php endif;?>
	
    </div><!-- /.content-wrapper -->
    <?php require_once("../config/footer.php"); ?>    </div><!-- ./wrapper -->
</div><!-- /.row -->
		
<!-- Main row -->

	<script src="<?=$hosted;?>/sistema/assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?=$hosted;?>/sistema/assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?=$hosted;?>/sistema/assets/plugins/fastclick/fastclick.min.js"></script>
	
	<!-- Charts -->
	<script src="<?=$hosted;?>/sistema/assets/plugins/chartjs/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="<?=$hosted;?>/sistema/assets/plugins/morris/morris.min.js"></script>
	
    
    <!-- AdminLTE App -->
    <script src="<?=$hosted;?>/sistema/assets/dist/js/app.min.js"></script>
	<script src="<?=$hosted;?>/sistema/assets/dist/js/demo.js"></script>
  
   <!-- Slimscroll -->
    <script src="<?=$hosted;?>/sistema/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="<?=$hosted;?>/sistema/assets/js/maskinput.js"></script>
    <script src="<?=$hosted;?>/sistema/assets/js/jmask.js"></script>
    <script src="<?=$hosted;?>/sistema/js/action_triang.js"></script>
    <script src="<?=$hosted;?>/sistema/js/controle.js"></script>
    <script src="<?=$hosted;?>/sistema/js/jquery.cookie.js"></script>
    <script src="<?=$hosted;?>/sistema/js/functions.js"></script>
    <script src="<?=$hosted;?>/sistema/js/action_empresas.js"></script>
    <!-- SELECT2 TO FORMS-->
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
	<!-- Validation -->
    <!--<script src="<?=$hosted;?>/js/jquery-validation/dist/jquery.validate.min.js"></script>-->
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="<?=$hosted;?>/sistema/assets/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
	
	<script src="<?=$hosted;?>/sistema/assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?=$hosted;?>/sistema/assets/plugins/datatables/dataTables.bootstrap.min.js"></script> 
		

	<script type="text/javascript">
		$(function(){

			$("#checkboximp").bootstrapToggle();
			
			// Get context with jQuery - using jQuery's .get() method.
	        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
	        var pieChart = new Chart(pieChartCanvas);
	        var PieData = [
	          <?=$jparam;?>
	        ];
	        var pieOptions = {
	          //Boolean - Whether we should show a stroke on each segment
	          segmentShowStroke: true,
	          //String - The colour of each segment stroke
	          segmentStrokeColor: "#fff",
	          //Number - The width of each segment stroke
	          segmentStrokeWidth: 2,
	          //Number - The percentage of the chart that we cut out of the middle
	          percentageInnerCutout: 50, // This is 0 for Pie charts
	          //Number - Amount of animation steps
	          animationSteps: 100,
	          //String - Animation easing effect
	          animationEasing: "easeOutBounce",
	          //Boolean - Whether we animate the rotation of the Doughnut
	          animateRotate: true,
	          //Boolean - Whether we animate scaling the Doughnut from the centre
	          animateScale: false,
	          //Boolean - whether to make the chart responsive to window resizing
	          responsive: true,
	          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
	          maintainAspectRatio: true,
	          //String - A legend template
	          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
	        };
	        //Create pie or douhnut chart
	        // You can switch between pie and douhnut using the method below.
	        pieChart.Doughnut(PieData, pieOptions);

	        // Bar Chart Morris
	        var bar = new Morris.Bar({
	          element: 'bar-chart',
	          resize: true,
	          data: [
	           <?=$datahis; ?>
	          ],
	          barColors: ['#3C8DBC'],
	          xkey: 'y',
	          ykeys: ['a'],
	          labels: ['IRPF'],
	          hideHover: 'auto'
	        });

	        //DONUT CHART
			var donut = new Morris.Donut({
				element: 'irpf_pagos',
				resize: true,
				colors: ["#00a65a", "#f56954", "#3c8dbc"],
				data: [
				<?=$dado_pagos;?>
				],
				hideHover: 'auto'
			});

		});
		
		setTimeout(function(){
			//$("#counters").load("../config/dash_index2.php");
			$("#alms").load(location.href+" #almsg");
			//$("#view_pendconf").load("view_pendconf.php?box=0");
            //$("#view_pendenv").load("view_pendenv.php?box=0");
            //$("#view_confenv").load("view_confenv.php?box=0");
                
		 },7500);

		$(".select2").select2({
			tags: true
		});

		$(".func").select2({
			tags: true,
			theme: 'classic'
		});
		
		$(document).on("click",".pesq", function(){
		 	$("#sel_emp").select2("val",$(this).data("emp"));
		 	$("#sel_imp").select2("val",$(this).data("imp"));
		 	//$("#pesq_conf").load("vis_impconferir.php");
		 	//console.log($(this).data("pesq"));
		 });

      	</script>
