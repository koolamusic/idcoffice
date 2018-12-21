<br><br><br><br><br><br><br>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
<footer style="background:#F8F8F8; padding: 10px; text-align:center;">
    <p>Copyright &copy; <?php if(isset($siteInfo['site_name'])){echo $siteInfo['site_name'];}else{echo 'Yoursite Name';}?>. <?php echo date("Y");?> All right reserved. Designed by: 
<a target="_blank" href="http://www.creativeweb.com.ng">CreativeWeb Nigeria</a></p>
</footer>
	<script src="<?php echo BASE_URL;?>user/assets/js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="<?php echo BASE_URL;?>user/assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="<?php echo BASE_URL;?>user/assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="<?php echo BASE_URL;?>user/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo BASE_URL;?>user/assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
    </script>
         <!-- Custom Js -->
    <script src="<?php echo BASE_URL;?>user/assets/js/custom-scripts.js"></script>
    <!-- Morris Chart Js -->
    <script src="<?php echo BASE_URL;?>user/assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="<?php echo BASE_URL;?>user/assets/js/morris/morris.js"></script>
    
    
</body>
</html>