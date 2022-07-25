 <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Invoice #{{$data->receipt_number}}</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				/* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #caf0f8;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #caf0f8;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #caf0f8;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}
			.header{
				font-family: Arial, Helvetica, sans-serif;
				font-size: 10px;
				text-align: center;
				align-items: center;
			}
			.row{
				text-align: center;
				align-items: center;
				font-size: 12px;
			}
			th{
            background: #1976d1;
            height: 20px;
        	}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			} 
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
							<?php $date = date('d-m-Y', strtotime($data->ordered_at));?>
								<td style="font-size: 13px;">
									Invoice #:{{$data->receipt_number}}<br />
									Created: {{$date}}<br />
									Shop: JONGTENH
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<thead>
                        <tr>
                            <th style="width: 2%;" class="header">No.</th>
                            <th style="width: 15%"class="header">Invoice ID</th>
							<th style="width: 15%"class="header">Customer</th>
							<th style="width: 10%"class="header">Product</th>
							<th style="width: 10%"class="header">Price</th>
							<th style="width: 10%"class="header">Qty</th>
                            <th style="width: 15%"class="header">Discount (%)</th>
                        </tr>
                </thead>
				 <tbody>
				 	<?php $no = 1; ?>
					@foreach($data->details as $item)
						<tr>
						<td class="row"> {{$no}}</td>
						<td class="row"> #{{$data->receipt_number}}</td>
						<td class="row"> {{$data->customer->user->name}}</td>
						<td class="row"> {{ $item->product->name}}</td>
						<td class="row"> {{ $item->unit_price}}</td>
						<td class="row"> {{ $item->qty}}</td>
						<td class="row"> {{ $data->discount}}(%)</td>
						<?php $no++; ?>
						</tr>
					@endforeach
				</tbody>
				<tbody>
				<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="width: 5%; font-size: 13px; ">Total Price: {{$data->total_price}}Riel</td>
				</tr>
				</tbody>	
			</table>
		</div>
	</body>

</html>