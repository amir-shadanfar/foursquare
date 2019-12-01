@extends('layouts.assets')
@section('body')
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-12 col-md-4 bd-sidebar">
                <div class="accordion" id="accordionParent">
                    @foreach($categories as $category)
                        <div class="card">
                            <div class="card-header" id="heading{{$loop->iteration}}">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapse{{$loop->iteration}}">
                                        <img src="{{$category['icon']['prefix'].'32'.$category['icon']['suffix']}}"/>
                                        {{$category['name']}}
                                        <span class="badge badge-secondary badge-pill">{{count($category['categories'])}}</span>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse{{$loop->iteration}}" class="collapse"
                                 aria-labelledby="heading{{$loop->iteration}}"
                                 data-parent="#accordionParent">
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($category['categories'] as $subCategory)
                                            <li class="list-group-item sub_category"
                                                data-name="{{$subCategory['name']}}">
                                                <img src="{{$subCategory['icon']['prefix'].'32'.$subCategory['icon']['suffix']}}"/>
                                                <a href="#">
                                                    {{$subCategory['name']}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- /sidebar -->
            <!-- Page Content -->
            <div class="col-12 col-md-8">
                <h2 id="title">Explore Valletta</h2>
                <hr>
                <ul id="content"></ul>
            </div>
            <!-- /Page Content -->
        </div>
    </div>
    <!-- #overlay -->
    <div id="overlay" style="display:none;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('.sub_category').on('click', function () {
                let THIS = $(this);
                let ul = $('#content');
                let sub_category = THIS.data('name');
                $.ajax({
                    url: '{{route('foursquare.getInfo')}}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        category: sub_category,
                        _token: '{{csrf_token()}}'
                    },
                    beforeSend: function () {
                        $('#title').text('Valletta / ' + THIS.data('name'));
                        ul.find('li').remove();
                        $('#overlay').fadeIn(200);
                    },
                    complete: function () {
                        $('#overlay').fadeOut(200);
                    },
                    success: function (result) {
                        if (result.status === 200) {
                            if (result.data.groups[0].items.length < 1) {
                                ul.append('<li>' + sub_category + ' has not any items in this city! </li>');
                            } else {
                                let li = '';
                                $.each(result.data.groups[0].items, function (index, item) {
                                    li += '<li>' + item.venue.name + ' (' + item.venue.location.address + ')' + '</li>';
                                });
                                ul.append(li);
                            }
                        } else {
                            ul.append('<li>' + result.message + '</li>');
                        }
                    },
                    error: function (e) {
                        console.log(e.message);
                    }
                });
            });
        });
    </script>
@endsection
@section('css')
    <style>
        /*overlay*/
        #overlay {
            background: #ffffff;
            color: #666666;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 5000;
            top: 0;
            left: 0;
            float: left;
            text-align: center;
            padding-top: 25%;
            opacity: .80;
        }

        .container {
            background-color: #f1f1f1;
            padding: 20px;
        }

        .card {
            background-color: #4d4b4f;
        }

        .btn-link {
            color: #ffffff;
        }

        .btn-link:hover {
            color: #ffffff;
            text-decoration: underline overline;
        }

        a {
            color: #ffffff;
        }

        a:hover {
            color: #ffffff;
            text-decoration: underline overline;
        }

        .list-group-item {
            border: 1px solid rgba(216, 216, 216, 0.12);
            background-color: #4d4b4f;
        }
    </style>
@endsection