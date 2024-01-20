<!-- JAVASCRIPT -->
<script src="{{ url('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ url('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ url('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Required datatable js -->
<script src="{{ url('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ url('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ url('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<script src="{{ url('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ url('assets/js/pages/datatables.init.js') }}"></script>

<!-- twitter-bootstrap-wizard js -->
<script src="assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<script src="assets/libs/twitter-bootstrap-wizard/prettify.js"></script>
@stack('bootstrap-scripts')

<!-- form wizard init -->
<script src="assets/js/pages/form-wizard.init.js"></script>

<script src="{{ url('assets/js/app.js') }}"></script>

<!-- Plugins js -->
<script src="{{url('assets/libs/dropzone/min/dropzone.min.js')}}"></script>


<!-- Calendar init -->
<script src="{{asset('assets/js/pages/calendar.init.js')}}"></script>


<!--tinymce js-->
<script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script>

<!-- init js -->
<script src="{{asset('assets/js/pages/form-editor.init.js')}}"></script>

<!-- select2 js -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('script')
