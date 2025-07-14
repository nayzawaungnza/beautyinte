<!--**********************************
            Footer start
        ***********************************-->
<div class="footer">
    <!-- <div class="copyright">
        <p>Copyright &copy; Designed & Developed by <a href="https://themeforest.net/user/quixlab">Quixlab</a> 2018</p>
    </div> -->
</div>
<!--**********************************
            Footer end
        ***********************************-->
</div>
<!--**********************************
        Main wrapper end
    ***********************************-->

<!--**********************************
        Scripts
    ***********************************-->
<!-- <script src="../dashJs/bootstrap-datepicker.min.js"></script> -->
<!-- <script src="../dashJs/bootstrap-material-datetimepicker.js"></script> -->
<script src="../dashJs/bootstrap-timepicker.min.js"></script>
<script src="../dashJs/common.min.js"></script>
<script src="../dashJs/custom.min.js"></script>
<script src="../dashJs/daterangepicker.js"></script>
<script src="../dashJs/jquery-asColor.js"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="../dashJs/jquery-asColorPicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="../dashJs/jquery-asGradient.js"></script>
<script src="../dashJs/jquery-clockpicker.min.js"></script>

<script src="../dashJs/jquery-ui.min.js"></script>
<script>
    $(document).ready(() => {
        sideBtn = document.querySelectorAll('.sidebar-click')
        sideBtn.forEach(element => {
            element.addEventListener("click", function() {
                let pannel = $(this).find('ul.pannel');
                pannel.toggle(300, () => {
                    $(this).find('.arrow').toggleClass("has-arrow arrow-down");
                });
            })
        });

        const urlParams = new URLSearchParams(window.location.search);
        const param1 = urlParams.get('unauthorized_msg');
        if (param1 != null) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: param1,
                timer: 3000
            });
        }
    })
</script>


<script src="../dashJs/jquery.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script src="../dashJs/moment.js"></script>
<script src="../dashJs/moment.min.js"></script>
<script src="../dashJs/settings.js"></script>
<script src="../dashJs/styleSwitcher.js"></script>
<script src="../dashJs/sweetalert2.all.min.js"></script>


</body>

</html>