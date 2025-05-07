<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RAMS</title>
        <link rel="stylesheet" href="css/indexStyle.css">
    </head>

    <body>
        <nav class="navbar">
            <div class="nav-container">

                <div class="logo">
                    <img src="https://www.adaptivewfs.com/wp-content/uploads/2020/07/logo-placeholder-image.png" alt="Hotel Mangement System">
                    <h1>RAMS</h1>
                </div>

                <div class="nav-menu">
                    <a href="index.php#home">Home</a>
                    <a href="index.php#login">Login</a>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="index.php#contact">Contact</a>
                    <a href="index.php#about">About</a>
                </div>

            </div>
        </nav>

        <div class="parallax">

            <section id="home" class="parallax__header">
                <div class="parallax__layers">
                    <img src="https://images7.alphacoders.com/877/877723.jpg" loading="eager" alt="" class="parallax__layer-img">
                    <div class="parallax__layer-overlay"></div>
                </div>
                <div class="parallax__layer-title">
                    <h1 class="parallax__title">HOTEL MANAGEMENT SYSTEM</h1>
                </div>
            </section>

            <section id="login" class="section" style="margin-top: 30px;">
                <h2 class="section-title">Login to Dashboard</h2>

                <form id="login">
                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <p class="centered_text"><a href="#">Forgot Password?</a></p>
                    <button class="new-button" type="submit" style="width: 100%; color: ">Login</button>
                </form>

            </section>

            <section id="register" class="section" style="margin: 0 auto; text-align: center;">
                <h2 class="section-title" style="color: #4b4a45;">Don't have an account? Register Here</h2>

                <a href="register.php"><button class="new-button" style="padding: 10 10rem; color: #ffffff;">Register</button></a>

            </section>

            <section id="about" class="section">
                <h2 class="section-title">About Our System</h2>
                <div class="about-content">

                    <div class="about-text">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
            
                    <div class="about-features">
                        <h3>Key Features</h3>
                        <ul>
                            <li>INSERT FEATURE 1</li>
                            <li>INSERT FEATURE 2</li>
                            <li>INSERT FEATURE 3</li>
                            <li>INSERT FEATURE 4</li>
                            <li>INSERT FEATURE 5</li>
                            <li>INSERT FEATURE 6</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="contact" class="section">
                <h2 class="section-title">Contact Us</h2>
                <div class="contact-container">
            
                    <form>
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="new-button" style="width: 100%;">Send Message</button>
                    </form>
                
                    <div>
                        <p>Have any questions? Reach out to us via email, phone, or by filling out the contact form.</p>
                  
                        <div class="contact-info">
                            <div class="contact-method">
                                <h3><i class="fas fa-envelope"></i> Email</h3>
                                <p>INSERT SUPPORT EMAIL HERE</p>
                                <p>INSERT SUPPORT EMAIL HERE</p>
                            </div>
                            <div class="contact-method">
                                <h3><i class="fas fa-phone-alt"></i> Phone</h3>
                                <p>+63 998 888 8888</p>
                                <p>+63 999 999 9999</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <footer>
            <div style="padding: 10px auto;">
                <p>Aaron Jacalan  |  BS Computer Science - 2</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
        <script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js"></script>
        <script type="text/javascript" src="js/indexJs.js"></script>

    </body>
</html>