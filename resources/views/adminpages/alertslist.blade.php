<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
 
  </head>
  <body>
    <div class="wrapper">
    @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container">
          <div class="page-inner">
     
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    
                      <div class="card-body">
                        <div class="table-responsive">
                          <table
                            id="add-row"
                            class="display table table-striped table-hover"
                          >
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Alert Date</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($alerts as $alert)
                                        <tr class="user-row" id="alert-{{ $alert->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$alert->id}}
                                               
                                                <td id="name">{{$alert->title}}</td>  
                                               
                                                <td id="slug">{{$alert->message}}</td>
                                                <td id="slug">{{$alert->alert_date}}</td>

                                               
                                                 
                                            </tr>
                                            @php $counter++; @endphp
                                        @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
          </div>
        </div>

        @include('adminpages.footer')
      </div>
    </div>



    
    @include('adminpages.js')
    @include('adminpages.ajax')
   
  </body>
</html>
