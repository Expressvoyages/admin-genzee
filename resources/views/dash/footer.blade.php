<footer class="footer">
   © 2024 <span class="right"><a class="grey-text text-lighten-4" target="_blank" href="https://cruooze.com/">Cruooze Nigeria Limited</a></span>
</footer>

<script>
   $(document).ready(function () {
       $('#exportBtn').click(function () {
           var csv = [];
           var rows = $('.table tbody tr');
           rows.each(function (index, row) {
               var rowData = [];
               $(row).find('td').each(function (index, column) {
                   rowData.push($(column).text());
               });
               csv.push(rowData.join(','));
           });
           downloadCSV(csv.join('\n'), 'Customercare(Userdata).csv');
       });

       function downloadCSV(csv, filename) {
           var csvFile;
           var downloadLink;

           // CSV file
           csvFile = new Blob([csv], { type: 'text/csv' });

           // Download link
           downloadLink = document.createElement('a');

           // File name
           downloadLink.download = filename;

           // Create a link to the file
           downloadLink.href = window.URL.createObjectURL(csvFile);

           // Hide download link
           downloadLink.style.display = 'none';

           // Add the link to DOM
           document.body.appendChild(downloadLink);

           // Click download link
           downloadLink.click();
       }
   });
</script>


<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('admin/js/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('admin/js/tether.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('admin/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('admin/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('admin/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('admin/js/sticky-kit.min.js')}}"></script>

<!--Custom JavaScript -->
<script src="{{asset('admin/js/custom.min.js')}}"></script>

</div>
<!-- End Page wrapper  -->

</div>
<!-- End Main Wrapper -->

</body>

</html>