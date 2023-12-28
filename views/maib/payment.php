<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;

//echo "<pre>"; var_dump($status); echo "</pre>"; exit;

?>
<div class="site-about">

<div class="row">
	<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-xs-none"></div>
	<div class="col-md-10 col-sm-10">
		<h1>Страница онлайн оплаты банковской картой</h1><br>
	</div>
</div>

<div class="row">
	<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-xs-none"></div>
	<div class="col-md-10 col-sm-10">
		<div class="row">

          <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 invoice-col" style="text-align: left">
          	 Номер счёта: <b>TEST-123</b><br>
          	 Статус: <b>Актуальный</b>
            <br>Дата счёта: <b>22.01.2023</b>
            <br>Срок действия счета: <b>27.01.2023</b>
			</div>

          <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 invoice-col">
            <b></b>
            <address>
                            Продавец:<br><strong>Kappa SRL</strong>
                            <br>Chisinau, str. Columna, 84                                <br>Телефон: ‎‎37379760762                                <br>E-Mail: kappa.srl@invoicing.md                                <br>Код IDNO: 1019600052449                                <br>Код НДС: 453271                            </address>
          </div>
          <!-- /.col -->

          <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 invoice-col">
            <b></b>
            <address>
                            Покупатель:<br><strong>Delta SA</strong>
                            <br>Chisinau, bd. Stefan cel Mare 1                                <br>Телефон: 37369123456                                <br>E-Mail:&nbsp;facturare.delta.sa@gmail.com                                <br>Код IDNO: 0000000000001                                <br>Код НДС: 000001                            </address>
          </div>


		</div>
	</div>
</div>

<div class="row">
	<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-xs-none"></div>
	<div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12 mt-1">
		<i>&nbsp;Комментарий менеджера:</i>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td>Orele de lucru 10:00-18:00<br>Luni-Vineri</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="row mt-2">
	<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-xs-none"></div>
	<div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12">
		<div class="row">

			<div class="col-xs-11 col-lg-11 col-md-11 table">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Товар / Услуга</th>
							<th style="text-align:right">Цена за ед.</th>
							<th style="text-align:right">Кол-во</th>
							<th>Ед.</th>
							<th style="text-align:right">Итого</th>
						</tr>
					</thead>
					<tbody>

                        <tr>
							<td>1.</td>
							<td>Reparatia laptop</td>
							<td style="text-align:right">500.00 MDL</td>
							<td style="text-align:right">2</td>
							<td>pcs</td>
							<td style="text-align:right">1 000.00 MDL</td>
						</tr>
                        <tr>
							<td>2.</td>
							<td>Instalarea windows</td>
							<td style="text-align:right">600.00 MDL</td>
							<td style="text-align:right">1</td>
							<td>pcs</td>
							<td style="text-align:right">600.00 MDL</td>
						</tr>
                      </tbody>
				</table>
			</div>
			<!-- /.col -->
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-xs-none"></div>
                      <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12">

                      <!-- /.col -->
                      <div class="col-xs-6">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <tbody>
                              <tr>
                                <th style="width:70%">Итого</th>
                                <td align="right">1 920.00 MDL</td>
                              </tr>
                              <tr>
                                <th style="width:70%">Ставка НДС</th>
                                <td align="right">20.00 %</td>
                              </tr>
                              <tr>
                                <th style="width:70%">ИТОГО БЕЗ НДС</th>
                                <td align="right">1 600.00 MDL</td>
                              </tr>
                              <tr>
                                <th style="width:70%">ИТОГО НДС</th>
                                <td align="right">320.00 MDL</td>
                              </tr>
                              <tr>
                                <th style="color:green">ИТОГО К ОПЛАТЕ:</th>
                                <td style="color:green" align="right"><b>1 920.00 MDL</b></td>
                              </tr>

                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
</div>



<div class="row no-print">
	<div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 d-xxl-block d-xl-block d-lg-block d-md-block d-sm-none d-xs-none"></div>

                      <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-xs-12">

                    <div class="row">


                      <div class="col-3 d-xxl-block d-xl-block d-lg-none d-md-none d-sm-none d-xs-none mb-3">
                      </div>

                      <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 mt-2" style="text-align: left">
                      	<input id="accept" type="checkbox" value="1" style="cursor:pointer;"
                      		onchange="if(this.checked) {$('#go').prop('disabled', false)} else {$('#go').prop('disabled', true)}"> &nbsp; Я принимаю все условия оплаты, Соглашение о предоставлении услуг MAIB.                      </div>

                      <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1" style="text-align: right">
                        <form action="" method="get" enctype="multipart/form-data" target="_top">
                      	<div style="cursor:pointer">
                        <button id="go" type="submit" class="btn btn-success rounded-pill waves-effect waves-light me-3"
                        	style="width: 100%; margin: 15px 0 0 0" disabled="on">
		                	<span class="ms-1 fs-4"><i class="fa fa-credit-card"></i> &nbsp; Оплатить</span>
    					</button>
    					</div>
    					</form>
                      </div>

                      <div class="col-xxl-5 col-xl-5 col-lg-2 col-md-1 col-sm-12 col-xs-12">
                      </div>

                      <div class="row col-xxl-7 col-xl-7 col-lg-10 col-md-11 col-sm-12 col-xs-12 g-2" style="text-align: right">
                		<div class="col-xxl-6 col-xl-5 col-lg-7 col-md-6 col-sm-12 col-xs-12">
                        <p class="lead">Способы оплаты:<p>
                        </div>
                        <div class="col-xxl-6 col-xl-7 col-lg-5 col-md-6 col-sm-12 col-xs-12">
            	<img class="pull-left" src="/img/visa.png" title="Visa" alt="Visa" style="padding-right:5px;">
            	<img class="pull-left" src="/img/visa_electron.png" title="Visa Electron" alt="Visa Electron" style="padding-right:5px;">
            	<img class="pull-left" src="/img/mastercard.png" title="Mastercard" alt="Mastercard" style="padding-right:5px;">
            	<img class="pull-left" src="/img/maestro.png" title="Maestro" alt="Maestro">
                        </div>
                      </div>
                    </div>
</div>

</div>
