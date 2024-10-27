<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Collaboratask</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->

    <style>


        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            background: #1a202c;
            color: #f0f0f0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 20rem;
            background-color: #00355b;
               border-bottom: 4px solid #07385b;
        }
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .logo img {
            width: 30px; /* Adjust the width as needed */
            height: 30px; /* Adjust the height as needed */

        }
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        .nav-links a {
            color: #00e2f2;
            text-decoration: none;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #fff;
        }
        .hero {
    display: flex;
    justify-content: center;
    align-items: center; /* Center the content vertically */
    min-height: 70vh;
    background: linear-gradient(70deg, #0b387e, #050505); /* Gradient background */
    text-align: center;
    padding: 3.40rem;
    font-family: 'Leelawadee UI', sans-serif;
    position: relative;
}


/* Base styles: mobile-first */
.hero-box {
    background-color: #0044b8; /* Blue background for the box */
    border-radius: 20px; /* Rounded corners */
    padding: 2rem 1.5rem; /* Smaller padding for mobile screens */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Shadow for a box effect */
    max-width: 100%; /* Box can expand to full width on mobile */
    width: 100%; /* Full width of container */
    text-align: center; /* Center the content inside the box */
    position: relative; /* For internal positioning */
    z-index: 2;
}

/* Tablet (min-width: 768px) */
@media (min-width: 768px) {
    .hero-box {
        padding: 3rem 5rem; /* Adjust padding for larger screens */
        max-width: 1200px; /* Set a reasonable max-width for tablets */
        margin: 0 auto; /* Center the box horizontally */
    }
}

/* Desktop (min-width: 1024px) */
@media (min-width: 1024px) {
    .hero-box {
        padding: 3rem 10rem; /* Increase padding for more space */
        max-width: 1500px; /* Maximum width for desktops */
        margin: 0 auto; /* Center the box horizontally */
    }
}

/* Large desktops (min-width: 1440px) */
@media (min-width: 1440px) {
    .hero-box {
        padding: 3rem 20rem; /* Full padding for large desktops */
    }
}

.hero h1 {
    font-size: 8vw; /* Adjust based on viewport width */
    margin-bottom: 1rem;
    color: #fff;
    font-family: sans-serif;
    font-weight: normal;
    letter-spacing: 5px; /* Default letter-spacing */
}

/* Media query for tablets (768px and up) */
@media (min-width: 768px) {
    .hero h1 {
        font-size: 6vw; /* Scale down for tablets */
        letter-spacing: 8px; /* Increase letter-spacing slightly */
    }
}

/* Media query for desktops (1024px and up) */
@media (min-width: 1024px) {
    .hero h1 {
        font-size: 4rem; /* Use rem for larger, consistent size on desktop */
        letter-spacing: 10px; /* Increase letter-spacing for larger screens */
    }
}

.hero p {
    font-size: 1.50rem; /* Base size */
    font-weight: bold;
    margin-bottom: 2rem;
    margin-top: 3rem;
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-align: center;
    letter-spacing: 2px;
}

/* Medium devices (tablets, 768px and up) */
@media (max-width: 768px) {
    .hero p {
        font-size: 1.25rem; /* Slightly smaller */
        margin-bottom: 2rem; /* Adjust margins */
        margin-top: 2rem;
    }
}

/* Small devices (phones, 576px and up) */
@media (max-width: 576px) {
    .hero p {
        font-size: 1rem; /* Smaller size */
        margin-bottom: 2rem; /* Adjust margins */
        margin-top: 1.5rem;
    }
}

.hero button {
    background-color: #29eeff;
    color: #1a202c;
    padding: 1.25rem 2.25rem;
    font-size: 1.50rem;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s ease;
    width: auto; /* Allow button width to adjust with content */
    position: relative;
    z-index: 2;
    outline: none;
    display: inline-flex; /* To align the icon with the text */
    align-items: center; /* Align text and icon vertically */
    gap: 10px; /* Add some space between the text and the icon */
}

.button-icon {
    width: 16px; /* Set icon size */
    height: 15px;
}

.hero button::before {
    content: '';
    position: absolute;
    border-radius: 50px;
    z-index: 1;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: rgba(0, 0, 0, 0.1);
}

.hero button:hover {
    background-color: #00c4cc;
    transform: translateY(-3px);
}

.hero button:hover::before {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}

       .features {
    padding: 5rem;
    background: linear-gradient(113deg, #0b387e, #050505);


}

        .features h2 {
            text-align: center;
            font-size: 2.40rem;
            margin-bottom: 1rem;
            color: #00e2f2;
        }
        .features .feature-item {
            text-align: center;
            margin: 2rem 0;
        }
        .features .feature-item img {
            width: 100px;
            height: 100px;
            margin-bottom: 1rem;
        }
        .features .feature-item h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        .features .feature-item p {
            font-size: 1rem;
            color: #ccc;
        }
        .testimonials {
            padding: 2rem;
            background-color: #fff;
        }
        .testimonials h2 {
    text-align: center;
    font-size: 3rem; /* Default size for larger screens */
    margin-bottom: 3rem;
    background: linear-gradient(50deg, #ffffff 40%, #c0faff 50%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* For tablets */
@media (max-width: 768px) {
    .testimonials h2 {
        font-size: 2.5rem; /* Adjust size for tablets */
        margin-bottom: 2.5rem;
    }
}

/* For mobile devices */
@media (max-width: 480px) {
    .testimonials h2 {
        font-size: 2rem; /* Adjust size for mobile */
        margin-bottom: 2rem;
    }
}
.section-heading {
    text-align: left !important; /* Override any existing text alignment */
    margin-left: 16rem !important; /* Default margin for larger screens */
    font-size: 3rem !important; /* Default font size for larger screens */
    margin-bottom: 1rem !important;
    margin-top: 6rem !important;
}

/* Media query for tablets */
@media (max-width: 768px) {
    .section-heading {
        margin-left: 6rem !important; /* Adjust margin for tablets */
        font-size: 2.5rem !important; /* Adjust font size for tablets */
        margin-top: 4rem !important; /* Adjust margin-top for tablets */
    }
}

/* Media query for mobile devices */
@media (max-width: 480px) {
    .section-heading {
        margin-left: 1rem !important; /* Adjust margin for mobile */
        font-size: 2rem !important; /* Adjust font size for mobile */
        margin-top: 2rem !important; /* Adjust margin-top for mobile */
    }
}

.section-heads1 {
    text-align: left !important; /* Override any existing text alignment */
    margin-left: 16rem !important; /* Default margin for larger screens */
    font-size: 23px !important; /* Default font size for larger screens */
    margin-bottom: 4rem !important;
}

/* Media query for tablets */
@media (max-width: 768px) {
    .section-heads1 {
        margin-left: 6rem !important; /* Adjust margin for tablets */
        font-size: 20px !important; /* Adjust font size for tablets */
    }
}

/* Media query for mobile devices */
@media (max-width: 480px) {
    .section-heads1 {
        margin-left: 1rem !important; /* Adjust margin for mobile */
        font-size: 18px !important; /* Adjust font size for mobile */
    }
}


        .testimonials .testimonial-item {
            text-align: center;
            margin: 2rem 0;
        }
        .testimonials .testimonial-item img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        .testimonials .testimonial-item h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        .testimonials .testimonial-item p {
            font-size: 1rem;
            color: #ccc;
        }
        .testimonials {
    text-align: center; /* Centers the text */
    padding: 2rem; /* Adds padding around the section */
    background-color: #fff; /* Background color for the testimonials section */
    color: #f8f8f8; /* Text color */
    background: linear-gradient(55deg, #0b387e, #050505);
}
.boxes-container {
    display: flex; /* Use flexbox for alignment */
    justify-content: center; /* Centers the boxes horizontally */
    gap: 2rem; /* Space between the boxes (use rem for responsive spacing) */
    margin-top: 5.5rem; /* Space above the boxes */
    flex-wrap: wrap; /* Allows wrapping on smaller screens */
}

.box {
    background-color: #e5e5e557; /* Background color for the box */
    width: 100%; /* Use full width for smaller screens */
    max-width: 419px; /* Max width of the box */
    height: auto; /* Allow height to adjust based on content */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Initial box shadow */
    position: relative; /* For positioning of the pseudo-element and icon */
    padding: 3rem; /* Padding inside the box */
    border-radius: 40px; /* Rounded corners for the box */
    text-align: center; /* Center the text inside the box */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: glowing 1.5s infinite alternate; /* Glowing animation */
}

/* Hover effect for the box */
.box:hover {
    transform: scale(1.05); /* Slightly enlarge the box */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4); /* Increase shadow for a more pronounced effect */
}

.box::before {
    content: '';
    position: absolute;
    top: 1px;
    left: 50%;
    transform: translateX(-50%);
    width: 81px;
    height: 70px;
    background-color: rgba(255, 255, 255, 0.5); /* 50% transparent white */
    border-radius: 0 0 100px 100px;
    z-index: 1;
}

.box-icon {
    position: absolute;
    top: 10px; /* Adjust the position of the icon to fit inside the curve */
    left: 50%; /* Center the icon */
    transform: translateX(-50%); /* Center it horizontally */
    z-index: 2; /* Ensure the icon is on top of the curve */
    width: 50px; /* Adjust the size of the icon */
    height: 50px; /* Adjust the size of the icon */
    background-color: #000;
    border-radius: 30px;
}

.box-icon img {
    width: 100%; /* Make sure the image fills the container */
    height: 100%; /* Keep the height proportional */
}

/* Keyframes for glowing animation */
@keyframes glowing {
    80% {
        box-shadow:  0 0 15px rgba(0, 153, 255, 0.7), 0 0 15px rgba(0, 153, 255, 0.5);
    }
    100% {
        box-shadow:  0 0 15px rgba(0, 153, 255, 0.7), 0 0 15px rgba(0, 153, 255, 0.5);
    }
}

h3 {
    margin-top: 60px; /* Add space below the icon */
}

/* Media Queries for Responsive Design */
@media (max-width: 768px) {
    .boxes-container {
        flex-direction: column; /* Stack boxes vertically */
        gap: 2rem; /* Adjust gap for smaller screens */
        margin-left: 41px;
    }

    .box {
        width: 90%; /* Use most of the screen width */
        max-width: none; /* Remove max-width to fit smaller screens */
        height: auto; /* Adjust height based on content */
        padding: 2rem; /* Reduce padding for smaller screens */
    }
}

@media (max-width: 480px) {
    .box {
        padding: 1.5rem; /* Further reduce padding for small screens */
    }

    h3 {
        margin-top: 60px; /* Adjust spacing for smaller screens */
    }
}




.cta {
            padding: 2rem;
            background-color: #14191f;
            text-align: center;
        }
        .cta h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #00e2f2;
        }
        .cta button {
            background-color: #00e2f2;
            color: #1a202c;
            padding: 1rem 2rem;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .cta button:hover {
            background-color: #00c4cc;
        }
        footer {
            padding: 1rem;
            text-align: center;
            background-color: #14191f;
            color: #ccc;
        }
        .parent-container {
    position: relative; /* Necessary for the absolute positioning of the child */
}

.login-box {
    position: absolute;
    right: 132px; /* Position it to the right */
    top: 50%; /* Adjust vertical position (optional) */
    transform: translateY(-50%); /* Center vertically */
    padding: 10px 20px;
    border: 2px solid transparent;
    border-radius: 27px;
    background-clip: padding-box;
    text-decoration: none;
    color: #000;
    font-weight: bold;
    text-align: center;
    overflow: hidden;
    border-image: linear-gradient(254deg, #5888ee, #489cb4) 1;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.70rem 0.75rem;
    background-color: rgba(125, 125, 125, 0.5);
    backdrop-filter: blur(10px);
    position: fixed;
    top: 1rem;
    left: 0; /* No space on the left for mobile */
    right: 0; /* No space on the right for mobile */
    width: 98.5%; /* Full width on mobile */
    z-index: 1000;
    border-radius: 0; /* Remove border-radius for mobile */
    border: 1px solid rgba(255, 255, 255, 0.5);
}

/* For tablets and larger screens (768px and up) */
@media (min-width: 768px) {
    .header {
        left: 1rem; /* Add some space on the sides */
        right: 2rem;
        border-radius: 20px; /* Add border-radius */
    }
}

/* For larger screens (1024px and up) */
@media (min-width: 1024px) {
    .header {
        left: 2.50rem; /* Increase the left/right padding for larger screens */
        right: 5rem;
        border-radius: 30px; /* Increase border-radius for larger screens */
    }
}

/* For extra-large screens (1440px and up) */
@media (min-width: 1440px) {
    .header {
        left: 0rem; /* Apply the original large screen spacing */
        right: 0rem;
        border-radius: 50px; /* Larger border-radius */
    }
}

.nav-links {
    display: flex;
    gap: 20px; /* Space between navigation links */
}

.nav-links a {
    text-decoration: none;
    color: #fff; /* White text color */
    font-weight: bold;
    padding: 0.9rem 1rem; /* Padding for nav links */
    border-radius: 50px;
}

.login-box {
    display: inline-block;
    padding: 16px 25px;
    border: 1px solid transparent;
    border-color: #00e2f2;
    border-radius: 27px;
    background-clip: padding-box;
    text-decoration: none;
    color: #fff;
    transition: background 0.3s ease;
}

.login-box:hover {
    background: linear-gradient(130deg, #85f1ff, #1c6dd1);
    color: #000 !important;
}
.register-box {
    display: inline-block;
    padding: 16px 25px;
    border: 1px solid transparent;
    color: #000 !important; /* Text color */
    background: linear-gradient(163deg, #1c6dd1, #85f1ff); /* Gradient background */
    border-radius: 27px;
    background-clip: padding-box; /* Ensure background doesn't spill into the border */
    text-decoration: none;
    transition: background 1s ease, color 1s ease; /* Smooth transitions */
}

.register-box:hover {
    background: linear-gradient(130deg, #85f1ff, #1c6dd1); /* Change gradient direction on hover */
    color: #000; /* Keep the text black on hover */
}

.hero-image {
    margin-top: 4rem; /* Space between the button and the image */
    max-width: 100%;    /* Ensures the image doesn't overflow */
    height: auto;       /* Keeps the aspect ratio of the image */
    display: block;     /* Ensures the image is centered in the block context */
    margin-left: auto;  /* Centers the image horizontally */
    margin-right: auto; /* Centers the image horizontally */
    border-radius: 10px; /* Adds rounded corners */
}

.image-display img {
    max-width: 62%; /* Ensures the image doesn't overflow its container */
    height: auto; /* Keeps the aspect ratio of the image */
    transition: border-radius 0.3s; /* Optional: smooth transition */
}




.testimonials {
    padding: 6rem 1rem;
    text-align: center;
}



.testimonials1 {
    padding: 2rem 1rem;
    text-align: center;
}

.section-heading {
    font-size: 2.5rem;
    margin-bottom: 1rem;

}

.section-heads {
    font-size: 1.5rem;
    color: #666;
    margin-bottom: 3rem;
}

/* Styles for the boxes */
.boxes1-container {
    display: flex; /* Flexbox to align items in a row */
    justify-content: space-between; /* Distribute space between the boxes */
    gap: 20px; /* Space between boxes */
    max-width: 110rem; /* Limit the width of the container */
    margin: 0 auto; /* Center the container */
    padding: 0 2rem; /* Padding for responsiveness */
    flex-wrap: wrap; /* Allow items to wrap onto the next line */
}

.box1 {
    background-color: #e5e5e557; /* Background color for the box */
    flex: 1 1 calc(33.333% - 20px); /* Flexible width (3 boxes per row) with gap adjustment */
    max-width: 506px; /* Optional max width */
    height: auto; /* Allow height to adjust based on content */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Box shadow for a 3D effect */
    position: relative; /* For positioning the icon */
    padding: 2rem; /* Padding inside the box */
    border-radius: 40px; /* Rounded corners for the box */
    text-align: left; /* Align text to the left */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    min-width: 300px; /* Minimum width for smaller screens */
}

.box1:hover {
    transform: scale(1.05); /* Slightly enlarge the box */
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4); /* Increase shadow for a more pronounced effect */
}

/* Icon placed at the top left */
.box1-icon {
    position: absolute; /* Allows precise positioning of the icon */
    top: 10px; /* Adjusts the distance from the top of the box */
    left: 10px; /* Adjusts the distance from the left of the box */
    width: 70px; /* Size of the icon */
    height: 70px; /* Size of the icon */
}

.box1-icon img {
    width: 100%; /* Ensure the image fills the container */
    height: 100%; /* Keep the height proportional */
}

/* Adjust text content */
.box1-content {
    margin-top: 70px; /* Space the content below the icon */
    padding-left: 10px; /* Align the text with some padding to match the icon */
}

.box1 h3 {
    margin: 38px; /* Remove extra space above the heading */
    font-size: 2rem; /* Adjust font size for responsiveness */
}

.box1 p {
    font-size: 1.1rem; /* Adjust font size for responsiveness */
    margin-top: 1rem; /* Add space between heading and paragraph */
}

/* Media Queries */
@media (max-width: 768px) {
    .boxes1-container {
        padding: 0 1rem; /* Less padding on smaller screens */
    }

    .box1 {
        flex: 1 1 calc(50% - 20px); /* 2 boxes per row */
    }

    .box1 h3 {
        font-size: 1.8rem; /* Smaller font size */
    }

    .box1 p {
        font-size: 1rem; /* Smaller font size */
    }
}

@media (max-width: 480px) {
    .box1 {
        flex: 1 1 100%; /* 1 box per row */
        margin-bottom: 20px; /* Space between boxes */
    }

    .box1 h3 {
        font-size: 1.5rem; /* Smaller font size */
    }

    .box1 p {
        font-size: 0.9rem; /* Smaller font size */
    }
}



footer {
    display: flex;
    flex-direction: column; /* Stack items vertically */
    justify-content: center;
    align-items: center;
    height: 100px;
    background-color: #2e2e2e;
    color: white;
}

footer img, footer i {
    margin-bottom: 10px; /* Add space between the icon and text */
}

footer p {
    margin: -9px;
}

.box {
        width: 80%; /* Wider boxes on small screens */
    }

    body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .icon-container {
    display: flex;
    justify-content: center; /* Centers the icons in the container */
    gap: 80px; /* Adjusted gap for better spacing */
    padding: 35px;
    margin-top: 50px;
}

/* Icon box styles */
.icon-box {
    width: 70px; /* Width of the icon box */
    height: 70px; /* Height of the icon box */
    display: flex; /* Flexbox for centering the image */
    justify-content: center; /* Center the image horizontally */
    align-items: center; /* Center the image vertically */
    background-color: #ffffff00; /* Transparent background color */
    border-radius: 20px; /* Rounded corners for the box */
    transition: box-shadow 0.2s ease, background-color 0.2s ease; /* Smooth transition for color change */
}

/* Icon styles */
.icon-box img {
    width: 10vw; /* Size of the icon relative to the viewport width */
    height: 10vw; /* Size of the icon relative to the viewport width */
    max-width: 40px; /* Limit the maximum size */
    max-height: 40px; /* Limit the maximum size */
    cursor: pointer;
    border-radius: 10px; /* Rounded corners for the icon */
    transition: transform 0.1s ease, border 0.1s ease; /* Smooth transition for scale and border */
    box-sizing: border-box; /* Include border in size calculation */
}

/* Hover effect for the icon box */
.icon-box:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Add shadow on hover */
}

/* Hover effect for the icon image */
.icon-box:hover img {
    transform: scale(1.2); /* Slightly enlarge the icon on hover */
}

/* Media queries for different screen sizes */
@media (max-width: 768px) {
    .icon-box img {
        width: 8vw; /* Adjust size for smaller screens */
        height: 8vw; /* Adjust size for smaller screens */
    }
}

@media (max-width: 480px) {
    .icon-box img {
        width: 12vw; /* Adjust size for very small screens */
        height: 12vw; /* Adjust size for very small screens */
    }
}



p {
    font-family: 'Arial', sans-serif; /* Change font style */
    font-size: 25px; /* Adjust font size */
    line-height: 1.6; /* Adjust line spacing */
    margin-bottom: 20px; /* Space below the paragraph */
    text-align: left; /* Align the text */
    font-style: normal;
}
.b{
    text-align: left !important; /* Override any existing text alignment */
    margin-left: 0 !important;   /* Override any existing margin rules */
    font-size: 2rem;             /* Adjust font size if needed */
}





/*-------------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------*/











</style>









</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="{{ asset('dist/img/puzzle.png') }}" alt="CollaboraTask Logo">
            Collaboratask
        </div>


        <a href="{{ route('login') }}" class="login-box">Log in</a>

            <a href="{{ route('register') }}" class="register-box" >Register</a>
        </nav>
    </header>

    <section class="hero">

        <div class="hero-box">
            <h1>Save time working with
                <br>CollaboraTask,</h1>
            <p>Embark on an era of mastery and ease.
                Together we achieve more collaboration turns tasks into triumphs.
                 </p>

            <a href="{{ route('login') }}"><button>Get Started
            <img src="dist\img\arrow.png" alt="icon" class="button-icon">
            </button></a>

            <div class="hero-image">
            <img src="dist/img/LPF.png" alt="Hero Image" class="hero-image">
        </div>
    </section>

    <section class="features">
        <h2><span style= "color: white;">Visualize your</span> projects and tasks </h2>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icon Image Switcher</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .icon-container {
    display: flex;
    justify-content: center; /* Centers the icons in the container */
    gap: 80px; /* Adjusted gap for better spacing */
    padding: 35px;
    margin-top: 50px;
}

/* Icon box styles */
.icon-box {
    width: 70px; /* Width of the icon box */
    height: 70px; /* Height of the icon box */
    display: flex; /* Flexbox for centering the image */
    justify-content: center; /* Center the image horizontally */
    align-items: center; /* Center the image vertically */
    background-color: #ffffff00; /* Transparent background color */
    border-radius: 20px; /* Rounded corners for the box */
    transition: box-shadow 0.2s ease, background-color 0.2s ease; /* Smooth transition for color change */
}

/* Icon styles */
.icon-box img {
    width: 40px; /* Size of the icon */
    height: 40px; /* Size of the icon */
    cursor: pointer;
    border-radius: 10px; /* Rounded corners for the icon */
    transition: transform 0.1s ease, border 0.1s ease; /* Smooth transition for scale and border */
    box-sizing: border-box; /* Include border in size calculation */
}

/* Hover effect for the icon box */
.icon-box:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Add shadow on hover */

}

/* Hover effect for the icon image */
.icon-box:hover img {
    transform: scale(1.2); /* Slightly enlarge the icon on hover */

}

p {
    font-family: 'Arial', sans-serif;
    font-size: 18px; /* Default font size for small screens */
    line-height: 1.5; /* Default line spacing */
    margin-bottom: 15px; /* Default margin for small screens */
    text-align: left;
    font-style: normal;
}

/* For tablets and larger screens */
@media (min-width: 768px) {
    p {
        font-size: 22px; /* Increase font size */
        line-height: 1.6; /* Adjust line spacing */
        margin-bottom: 18px; /* Increase space below the paragraph */
    }
}

/* For desktops and larger screens */
@media (min-width: 1024px) {
    p {
        font-size: 25px; /* Larger font size for desktops */
        line-height: 1.7; /* Further adjust line spacing */
        margin-bottom: 20px; /* Add more space below the paragraph */
    }
}

.b{
    text-align: left !important; /* Override any existing text alignment */
    margin-left: 0 !important;   /* Override any existing margin rules */
    font-size: 2rem;             /* Adjust font size if needed */
}

























 </style>
<div class="icon-container">
    <div class="icon-box" onclick="changeBoxColor(this)">
        <img src="/dist/img/kanban.png" alt="Kanban" onclick="changeImage('kanban')" />
    </div>
    <div class="icon-box" onclick="changeBoxColor(this)">
        <img src="/dist/img/graph.png" alt="graph" onclick="changeImage('graph')" />
    </div>

    <div class="icon-box" onclick="changeBoxColor(this)">
        <img src="/dist/img/list.png" alt="List" onclick="changeImage('list')" />
    </div>
    <div class="icon-box" onclick="changeBoxColor(this)">
        <img src="/dist/img/calendar.png" alt="Calendar" onclick="changeImage('calendar')" />
    </div>
</div>

<div class="image-display">
    <img id="main-image" src="/dist/img/blur.png" alt="Kanban" />

</div>


<script>
        function changeBoxColor(clickedBox) {
            // Reset all icon boxes to default color
            const allBoxes = document.querySelectorAll('.icon-box');
            allBoxes.forEach(box => {
                box.style.backgroundColor = '#ffffff00'; // Default color
            });

            // Change the background color of the clicked box
            clickedBox.style.backgroundColor = 'rgb(97 97 255)'; // New color on click
        }

        // Existing changeImage function can go here
        function changeImage(icon) {
            var image = document.getElementById('main-image');

            // Map icon names to images and border radius values
            switch (icon) {
                case 'kanban':
                    image.src = '/dist/img/k.png';
                    image.alt = 'Kanban Board';
                    image.style.borderRadius = '15px'; // Specific border radius for kanban
                    break;
                case 'graph':
                    image.src = '/dist/img/g.png';
                    image.alt = 'Graph View';
                    image.style.borderRadius = '15px'; // Specific border radius for timeline
                    break;

                case 'list':
                    image.src = '/dist/img/l.png';
                    image.alt = 'List View';
                    image.style.borderRadius = '15px'; // Specific border radius for list
                    break;
                case 'calendar':
                    image.src = '/dist/img/c.png';
                    image.alt = 'Calendar View';
                    image.style.borderRadius = '15px'; // Specific border radius for calendar
                    break;
            }
        }
    </script>
    </section>

    <section class="testimonials">
    <h2>Get the Full Process System<br>use Our Software</h2>

    <div class="boxes-container">

        <div class="box">
            <img src="/dist/img/collaborate.png" alt="Icon 1" class="box-icon">
            <h3>Collaborate</h3>
            <p>Collaboration combines diverse skills and perspectives, boosting creativity and
                better solutions. It fosters shared responsibility, enabling everyone to contribute
                toward common goals.</p>
        </div>
        <div class="box">
            <img src="/dist/img/control.png" alt="Icon 2" class="box-icon">
            <h3>Take control of it</h3>
            <p>Create your own homepage by dragging and dropping personalized widgets.</p>
        </div>
        <div class="box">
            <img src="/dist/img/comms.png" alt="Icon 3" class="box-icon">
            <h3>Easy communication</h3>
            <p>Easy communication enables teams to share updates and feedback efficiently,
                ensuring alignment on goals. It allows real-time discussions, reducing the
                need for meetings.</p>
        </div>
    </div>

</section>

<section class="testimonials">
    <h2 class="section-heading">How It Works</h2>
    <h2 class="section-heads1">CollaboraTask streamlines task management, enabling your
        <br> team to collaborate effortlessly and meet deadlines efficiently.</h2>

    <!-- Add the boxes-container here -->
    <div class="boxes1-container">
        <div class="box1">
            <img src="/dist/img/iconnew2.png" alt="iconnew2" class="box1-icon">
            <h3>Sign Up & Set Up</h3>
            <p>Getting started with Collaboratask is easy. Simply sign up for an account
                using your email </p>
        </div>
        <div class="box1">
            <img src="/dist/img/iconnew.png" alt="iconnew" class="box1-icon">
            <h3>Create Tasks</h3>
            <p>Break down your work into manageable tasks.</p>
        </div>
        <div class="box1">
            <img src="/dist/img/iconnew1.png" alt="iconnew1" class="box1-icon">
            <h3>Collaborate & Communicate</h3>
            <p>With Collaboratask, you can chat with your team members in real-time
                and leave comments on tasks
            </p>
        </div>
    </div>
</section>




    <section class="cta">
        <h2>Ready to Get Started?</h2>
        <a href="{{ route('register') }}"><button>Sign Up Now</button></a>
    </section>


    <footer>
    <img src="/dist/img/puzzle.png" alt="Icon" style="width: 50px; height: 50px;"/> <!-- Adjust size as needed -->
    <p>&copy; 2024 Collaboratask. All rights reserved.</p>
    <br>
    <p style="font-size: 12px;">&copy; Published: October 2024</p>

</footer>

</body>
</html>
