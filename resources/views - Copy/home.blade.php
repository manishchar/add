@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- Page-Title -->
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="btn-group pull-right">
          <ol class="breadcrumb hide-phone p-0 m-0">
            <li class="breadcrumb-item"><a href="#">Upcube</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
        <h4 class="page-title">Dashboard</h4>
      </div>
    </div>
  </div>
  <!-- end page title end breadcrumb -->
  <div class="row">
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-white"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cart-outline text-danger"></i></span>
        <div class="mini-stat-info text-right text-muted"> <span class="counter text-danger">15852</span> Total Sales </div>
        <p class="mb-0 m-t-20 text-muted">Total income: $22506 <span class="pull-right"><i class="fa fa-caret-up m-r-5"></i>10.25%</span></p>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-success"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-currency-usd text-success"></i></span>
        <div class="mini-stat-info text-right text-white"> <span class="counter text-white">956</span> New Orders </div>
        <p class="mb-0 m-t-20 text-light">Total income: $22506 <span class="pull-right"><i class="fa fa-caret-up m-r-5"></i>10.25%</span></p>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-white"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-cube-outline text-warning"></i></span>
        <div class="mini-stat-info text-right text-muted"> <span class="counter text-warning">5210</span> New Users </div>
        <p class="mb-0 m-t-20 text-muted">Total income: $22506 <span class="pull-right"><i class="fa fa-caret-up m-r-5"></i>10.25%</span></p>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="mini-stat clearfix bg-info"> <span class="mini-stat-icon bg-light"><i class="mdi mdi-currency-btc text-info"></i></span>
        <div class="mini-stat-info text-right text-light"> <span class="counter text-white">20544</span> Unique Visitors </div>
        <p class="mb-0 m-t-20 text-light">Total income: $22506 <span class="pull-right"><i class="fa fa-caret-up m-r-5"></i>10.25%</span></p>
      </div>
    </div>
  </div>
  
  <!-- end row -->
</div>
@endsection 