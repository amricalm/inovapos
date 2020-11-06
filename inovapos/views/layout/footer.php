            <div style="clear:both;"></div>
        </div> <!-- End .container_12 -->
		
           
        <!-- Footer -->
        <div id="footer">
        	<div class="container_12">
            	<div class="grid_12">
                	<!-- You can change the copyright line for your own -->
                	<p>&copy; <?php echo date("Y") ?> <a href="http://www.andhana.com" title="Kunjungi untuk mengetahui perkembangan Software">andhana</a>, InovaPOS <?php echo 'v.'.$versi; ?></p>
        		</div>
            </div>
            <div style="clear:both;"></div>
        </div> <!-- End #footer -->
        <script type="text/javascript">
        function iclose()
        {
            $.prettyPhoto.close();
            window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
            //alert('x');
        }      

        </script>
	</body>
</html>