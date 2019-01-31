<?php $settings = \DB::table('settings')->where('id', 1)->first(); ?>
@extends('admin')

@section('content')
<div class="top-bar">
	<h3>Последние 20 платежей</h3>
</div>
<div class="well no-padding">
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>Пользователь</th>
				<th>Пополнил</th>
			</tr>
		</thead>
	  <tbody>
		@if(!empty($a))
			@foreach($a as $b)
			  <tr>
				  <td>{{$b->id}}</td>
				  <td>{{$b->name}}</td>
				  <td>{{$b->amount}} {{$settings->sitewallet}}</td>
			  </tr>
			@endforeach
		@else
		<tr>
			<td> <b>Данных нет..</b> </td>
		</tr>
		@endif
	  </tbody>
	</table>
</div>
@stop
