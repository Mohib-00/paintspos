<script src="{{asset('lite/assets/js/core/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('lite/assets/js/core/popper.min.js')}}"></script>
<script src="{{asset('lite/assets/js/core/bootstrap.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/chart.js/chart.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/chart-circle/circles.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/datatables/datatables.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
<script src="{{asset('lite/assets/js/plugin/jsvectormap/world.js')}}"></script>

<script src="{{asset('lite/assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

<script src="{{asset('lite/assets/js/kaiadmin.min.js')}}"></script>

<script src="{{asset('lite/assets/js/setting-demo.js')}}"></script>
<script src="{{asset('lite/assets/js/demo.js')}}"></script>

<script>
  $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#177dff",
    fillColor: "rgba(23, 125, 255, 0.14)",
  });

  $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#f3545d",
    fillColor: "rgba(243, 84, 93, .14)",
  });

  $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
    type: "line",
    height: "70",
    width: "100%",
    lineWidth: "2",
    lineColor: "#ffa534",
    fillColor: "rgba(255, 165, 52, .14)",
  });
</script>


  <script src="{{asset('lite/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
  <script src="{{asset('lite/assets/js/plugin/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('lite/assets/js/kaiadmin.min.js')}}"></script>
  <script src="{{asset('lite/assets/js/setting-demo2.js')}}"></script>
  <script>
    $(document).ready(function () {
      // Add Row
      $("#add-row").DataTable({
        pageLength: 5,
      });

      var action =
        '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

      $("#addRowButton").click(function () {
        $("#add-row")
          .dataTable()
          .fnAddData([
            $("#addName").val(),
            $("#addPosition").val(),
            $("#addOffice").val(),
            action,
          ]);
        $("#addRowModal").modal("hide");
      });
    });
  </script>