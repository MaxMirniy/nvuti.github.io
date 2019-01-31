<!DOCTYPE html>

<html lang="ru" data-textdirection="ltr" class="loaded"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="referrer" content="no-referrer">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="/files/favicon.ico" />
    <title>Cubbet check game №{{$game->id}}</title>
	  <meta name="description" content="Что такое Nvuti? Сервис мгновеных игр, где шанс выигрыша указываете сами. Быстрые выплаты без комиссий и прочих сборов.">
        <meta name="keywords" content="">
        <meta name="author" content="Nvuti.com">
    <link rel="apple-touch-icon" href="">
    <link rel="shortcut icon" type="image/x-icon" href="">
    <link href="../files/1.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="../files/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../files/style.min.css">
    <link rel="stylesheet" type="text/css" href="../files/font-awesome.min.css">

    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="../files/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="../files/app.min.css">
    <link rel="stylesheet" type="text/css" href="../files/colors.min.css">

    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../files/style.css">
    <!-- END Custom CSS--><script src="https://code.jquery.com/jquery-latest.js"></script>
  </head>
  <body data-open="click" data-menu="horizontal-menu" data-col="2-columns" class="horizontal-layout horizontal-menu 2-columns    menu-expanded " cz-shortcut-listen="true">

 



    <div class="app-content container center-layout" style="padding-right:0px!important;">
      <div class="content-wrapper" style="width:102%">
       
        <div class="content-body"><!--native-font-stack -->





<section id="description-list-alignment">


<div class="row">
    <!-- Description lists horizontal -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><b>GAME CARD</b><small class="text-muted " style='font-size:90%'> #{{$game->id}}</small></h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div class="card-text">
                        
                        <dl class="row">
						<div class="table-responsive" >
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID Игрокa</th>
                                <th>Цель</th>
                                <th>Выпало</th>
                                <th>Сумма</th>
                                <th>Выигрыш</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="font-weight:600">{{$game->user_id}}</th>
                                <td style="font-weight:600">{{$game->arrow}}</td>
                                <td style="font-weight:600">{{$game->number}}</td>
                                <td style="font-weight:600">{{$game->summ}} N</td>
                                <td style="font-weight:600">{{$game->profit}} N</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                            <dt class="col-sm-1 text-xs-right" ><br>Hash</dt>
                            <dd class="col-sm-11" >
						   <br>{{$game->hash}}</dd>
                            <dt class="col-sm-1 text-xs-right" >Salt 1</dt>
                            <dd class="col-sm-11" style="word-wrap:break-word;">{{$game->salt1}}</dd>
                           <dt class="col-sm-1 text-xs-right" >Number</dt>
						   <dd class="col-sm-11" style="word-wrap:break-word;">{{$game->number}}</dd>
						   <dt class="col-sm-1 text-xs-right" >Salt 2</dt>
                            <dd class="col-sm-11" style="word-wrap:break-word;">{{$game->salt2}}</dd>
				
						   <dt class="col-sm-4 offset-sm-4" style="margin-top:10px">
						 <button type="button " id="sucText" style="color:#fff;background: #6c7a89!important; border: 0px solid; " onclick="$('#sucText').html('Скопировано!')" class="btn   btn-block  btn-clipboard" data-clipboard-text="{{$game->salt1.$game->number.$game->salt2}}">Скопировать</button>
						
						 </dt> 
					
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Description lists horizontal-->
</div>
</section>
        </div>
      </div>
    </div>


    <script src="../files/clipboard.min.js" type="text/javascript"></script>
<script>
new Clipboard('.btn-clipboard');
</script>
  
<span id="sbmarwusasv5"></span></body></html>