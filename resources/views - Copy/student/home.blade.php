@extends('layouts.frontmaster')



@section('content')

 <section class="main-page-header lighten-4" style="background: url({{URL('/')}}/assets/frontend/images/cover-2.jpg)">

        <div class="container">

            <h1>say hello now!</h1>

            <h6>A web template for publishing these job postings</h6>

        </div>

</section>

  

    <section>

        <div class="container">

            <header class="section-header">

                <h3>Latest Jobs</h3>

            </header>

        @if(Session::has('message'))

            <div class="alert alert-success login-success"> 

                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                 {!! Session::get('message') !!} 

            </div>

        @endif

            <div class="card">

                <div class="card-body">

                @if(isset($jobmasterall) && !empty($jobmasterall))

                 @foreach($jobmasterall as $rowjobs)

                    <div class="item-jobpost">

                        <div class="row">

                            <div class="col-md-5">

                                <h4>

                                    <a href="{{URL::to('student-profile/'.$rowjobs->id.'/'.str_slug($rowjobs->JobTitle))}}">{{$rowjobs->JobTitle}}</a>

                                </h4>

                                <p>@php echo substr( strip_tags($rowjobs->Description) ,0,50) @endphp</p>

                                

                            </div>

                            <div class="col-md-5 jobpost-location">

                                <span><i class="md-pin m-r-10"></i> {{$rowjobs->Location}}</span>

                            </div>

                            <div class="col-md-2 jobpost-apply-btn">

                            

                                <a href="{{URL::to('jobApply/'.$rowjobs->org->orgSlug.'/'.$rowjobs->jobSlug)}}" class="btn btn-primary btn-block btn-outline btn-sm">Apply Now <i class="md-long-arrow-right m-l-10"></i></a>                              

                            </div>

                        </div>

                    </div>

                    @endforeach

                @endif



                <!-- <div class="item-jobpost">

                        <div class="row">

                            <div class="col-md-5">

                                <h4>

                                    <a href="{{URL::to('student-profile/'.$rowjobs->id.'/'.str_slug($rowjobs->JobTitle))}}">aaaaaaaa</a>

                                </h4>

                                <p>aaaaa</p>

                                

                            </div>

                            <div class="col-md-5 jobpost-location">

                                <span><i class="md-pin m-r-10"></i>lllll</span>

                            </div>

                            <div class="col-md-2 jobpost-apply-btn">

                            

                               <a href="{{URL::to('student-profile/'.$rowjobs->id.'/'.str_slug($rowjobs->JobTitle))}}" class="btn btn-primary btn-block btn-outline btn-sm">Apply Now <i class="md-long-arrow-right m-l-10"></i>

                               </a>

                              

                            </div>

                        </div>

                    </div> -->



                </div>

            </div>

           

        </div>

    </section>

@endsection 