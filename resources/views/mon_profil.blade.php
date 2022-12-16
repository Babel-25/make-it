@extends('user')
@section('content')
    <div class="row mt">
        <div class="col-lg-12">
            <div class="form-panel">
                <h4 class="mb"><i class="fa fa-angle-right"></i> Mon Profil</h4>
                <form class="form-horizontal style-form" method="get" action="#">
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label"> Nom </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="" placeholder="ATI">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label"> Prénom(s) </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="" placeholder="Kofi">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label"> Contact </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="" placeholder="91 09 11 22">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label"> Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="" placeholder="kfs@gmail.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label"> Sexe</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="" placeholder="Masculin" readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-round btn-success btn-block">
                        Modifier</button>



                </form>
            </div>
        </div>
        <!-- col-lg-12-->
    </div>
@endsection
