    <!--============================= FOOTER =============================-->
    <footer class="main-block dark-bg" style="padding:40px;">
    <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="copyright">
                        <p>Inlivery Copyright © 2020 | All rights reserved. </p>                        
                    </div>
                </div>
<div class="col-md-3">
                    <div class="copyright">
                        
                        <ul style="
    margin: 0;
">
                            <li><a href="#"><span class="ti-facebook"></span></a></li>
                            <li><a href="#"><span class="ti-twitter-alt"></span></a></li>
                            <li><a href="#"><span class="ti-instagram"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--//END FOOTER -->




    <!-- jQuery, Bootstrap JS. -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
   
    <script src="js/bootstrap.min.js"></script>
    <!-- Magnific popup JS -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- Swipper Slider JS -->
    <script src="js/swiper.min.js"></script>

    <script>
        $(window).scroll(function() {
            // 100 = The point you would like to fade the nav in.
            if ($(window).scrollTop() > 100) {
                $('.fixed').addClass('is-sticky');
            } else {
                $('.fixed').removeClass('is-sticky');
            };
        });

        $(document).on("click","#loginButton",function(){
            var emailPattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            let email = $("input[name='email']").val();
            let password = $("input[name='password']").val();
            let errorMessage;
            if(email && password){
                // if(emailPattern.test(email) !=true){
                //     errorMessage = 'Please enter a valid e-mail.';
                //     showErrorMessage(errorMessage);
                //     return false;
                // }
                errorMessage = '';
                
                $.ajax({
                    url:'login.php',
                    method:'POST',
                    data:{email,password},
                    success:function(response){
                        const {status, msg} = JSON.parse(response);
                        if(status == true){
                            alert("login Success");
                            location.reload();
                        }else{
                            alert("Something went wrong, Please try again later")
                        }
                    }
                });

            }else{
                errorMessage = "Both e-mail and password is required."
                
            }
            showErrorMessage(errorMessage)
        })

        function showErrorMessage(msg) {
            $(".errorMessage").html(msg);
        }
    </script>
</body>

</html>