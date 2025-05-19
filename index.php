<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doodly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <main class="bg-gray-100 w-full h-[70px] flex items-center justify-between px-4">
        <div class="text-lg font-semibold">Doodly</div>
    
        <nav class="flex gap-10 items-center justify-around py-7 ">
            <div>
                <form action="/search" method="GET" class="rounded-full w-[20rem] mr-10">
                    <input type="text" name="query" placeholder="Search..." required class="w-full p-2 rounded-full">
                </form>
            </div>
            
            <div class="flex gap-6 ">
                <a href="a" class="hover:text-purple-500">Planners</a>
                <a href="a"  class="hover:text-purple-500" > Journals & Notebooks</a>
                <a href="a" class="hover:text-purple-500">Sketchbook</a>
            </div>
    
            <div class="flex gap-4">
                <a href="login.php">
                <button class="bg-[#C1A2FF] rounded-full py-2 px-6 hover:bg-purple-400">Login</button></a>
                <a href="signup.php">
                <button class="bg-white  rounded-full py-2 px-6 hover:bg-purple-400">Sign Up</button></a>
            </div>
        </nav>
    </main>

     <!-- Hero section -->
    <section class="h-[550px] flex items-center justify-start text-left px-12 bg-[url('./hero.jpg')] bg-cover bg-center bg-no-repeat relative">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
        <div class="relative w-full max-w-4xl pl-12 text-white">
             <h1 class="text-4xl md:text-6xl font-bold">
               Plan,</h1>
               <h1 class="text-4xl md:text-6xl font-bold">
                Create,</h1>
                <h1 class="text-4xl md:text-6xl font-bold">
                    Inspire,</h1>

            <p class="mt-4 text-lg">
                Discover beautifully crafted planners, journals, and 
                <br>sketchbooks designed for creativity and productivity. <br>
                Personalize your space, organize your thoughts,<br> and make every page uniquely yours.            </p>
            <div class="mt-6">
                <a href="homepage.php"  class="bg-[#C1A2FF] text-white px-6 py-3 rounded-full text-lg font-semibold shadow-md hover:bg-purple-400 transition">
                    Shop Now
</a>
            </div>
        </div>
    </section>

 <!-- 2nd section -->
    <section class="h-[500px] flex items-start items-center justify-between text-left px-12 py-7 mt-[100px] mb-[100px] bg-[#F9F6FF] shadow-[0_4px_10px_rgba(193,162,255,0.2),0_-4px_10px_rgba(193,162,255,0.2)]">
        <!-- Left Side: Text Content -->
        <div class="flex flex-col space-y-4 ml-10">
            <div class="text-6xl font-bold">Planners <br>Made for You</div>
            <div class="text-lg">
                <p>Stay organized and inspired with our beautifully designed<br>
                    planners. Featuring customizable layouts, high-quality paper,<br>
                    and creative details, they help you plan your days effortlessly while<br>
                    adding a personal touch.
                </p>
            </div>
            <div>
                <a href='homepage.php?Planners' class="bg-[#C1A2FF] text-white px-6 py-3 rounded-full text-lg font-semibold shadow-md hover:bg-purple-400 transition">
                    Shop Now
</a>
            </div>
        </div>
        <!-- Right Side: Image -->
        <div class="ml-auto">
            <img src="Doodly images/sec1.jpg" alt="Example Image" class="w-[450px] h-[400px] rounded-lg shadow-md mr-10">
        </div>
    </section>
    

 <!-- 3rd section -->
<section class="h-[500px] flex items-center justify-between text-left px-12 py-7 mt-[100px] mb-[100px] bg-[#EDFAFF] flex-row-reverse shadow-[0_4px_10px_rgba(173,210,255,0.2),0_-4px_10px_rgba(173,210,255,0.2)]">
    <!-- Right Side: Text Content -->
    <div class="flex flex-col space-y-4 mr-10 ml-16">
        <div class="text-6xl font-bold">Journals & Notebooks  <br>for Every Story</div>
        <div class="text-lg">
            <p>Capture your thoughts, ideas, and creativity with our beautifully<br>
                 crafted journals and notebooks. Whether for daily reflections,<br>
                 sketches, or notes, each page is designed to inspire and last.
            </p>
        </div>
        <div>
            <a href="homepage.php?Journalsandnotebooks" class="bg-[#90E8FF] text-white px-6 py-3 rounded-full text-lg font-semibold shadow-md hover:bg-blue-300 transition">
                Shop Now
</a>
        </div>
    </div>
    <!-- Left Side: Image -->
    <div  class="ml-12">
        <img src="Doodly images/sec2.jpg" alt="Example Image" class="w-[450px] h-[400px] rounded-lg shadow-md mr-10">
    </div>
</section>






<!-- 4th section -->
<section class="h-[500px] flex justify-start items-center text-left px-12 py-7 mb-[100px] bg-[#FFF0EF]">
 <div class="flex flex-col space-y-4 ml-10">
            <div class="text-6xl font-bold">Sketchbooks<br>for Endless<br>Creativity</div>
            <div class="text-lg">
                <p>Let your ideas flow freely with our high-quality sketchbooks.<br>
                     Designed for artists, doodlers, and creatives, each page is <br>
                     smooth, durable, and ready for your imagination whether you're<br>
                      sketching, journaling, or experimenting with new designs.
                </p>
            </div>
            <div>
                <a href="homepage.php?Sketchbooks" class="bg-[#FFA197] text-white px-6 py-3 rounded-full text-lg font-semibold shadow-md hover:bg-purple-400 transition">
                    Shop Now
</a>
            </div>
        </div>
        <!-- Right Side: Image -->
        <div class="ml-auto">
            <img src="Doodly images/sec3.jpg" alt="Example Image" class="w-[450px] h-[400px] rounded-lg shadow-md mr-10 ">
        </div>


</section>

  <!-- Best Sellers! -->
  <section class="h-[500px] flex flex-col px-12 pt-7 mb-[60px] bg-[#F9F9F9]">
    <h2 class="font-bold text-2xl">Best Sellers!</h2>
    
    <div class="flex justify-center space-x-6 mt-5">
        <!-- Product 1 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden h-[350px] w-[275px]">
            <img src="https://via.placeholder.com/275x200" alt="Product 1" class="w-full h-[200px] object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold">Product Name</h3>
                <p class="text-gray-600">Price: 250 MAD</p>
                <a href="#" class="block mt-3 mb-3 bg-blue-500 text-white text-center py-2 rounded hover:bg-blue-600 transition">View Product</a>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden h-[350px] w-[275px]">
            <img src="https://via.placeholder.com/275x200" alt="Product 2" class="w-full h-[200px] object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold">Product Name</h3>
                <p class="text-gray-600">Price: 300 MAD</p>
                <a href="#" class="block mt-3 bg-blue-500 text-white text-center py-2 rounded hover:bg-blue-600 transition">View Product</a>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden h-[350px] w-[275px]">
            <img src="https://via.placeholder.com/275x200" alt="Product 3" class="w-full h-[200px] object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold">Product Name</h3>
                <p class="text-gray-600">Price: 150 MAD</p>
                <a href="#" class="block mt-3 bg-blue-500 text-white text-center py-2 rounded hover:bg-blue-600 transition">View Product</a>
            </div>
        </div>

        <!-- Product 4 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden h-[350px] w-[275px]">
            <img src="https://via.placeholder.com/275x200" alt="Product 4" class="w-full h-[200px] object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold">Product Name</h3>
                <p class="text-gray-600">Price: 400 MAD</p>
                <a href="#" class="block mt-3 bg-blue-500 text-white text-center py-2 rounded hover:bg-blue-600 transition">View Product</a>
            </div>
        </div>
    </div>
</section>




        <!-- Why you'll love us ?-->


<section class="bg-gray px-12 py-7 mb-[60px]">
    <div class="max-w-6xl mx-auto text-center">
        <h1 class="text-4xl font-semibold text-gray-900">Why You'll Love Us?</h1>
    </div>

    <div class="flex flex-col items-center gap-10 mt-20">
        <!-- Feature 1 (Now Creativity & Productivity) -->
        <div class="flex items-center max-w-lg">
            <img src="Doodly images/creativity.png" alt="Creativity" class="w-20 h-20 mr-6">
            <div class="w-full">
                <h3 class="text-2xl font-semibold text-gray-900">Boost Your Creativity & Productivity</h3>
                <p class="text-gray-600 text-base">Stay organized while unleashing your creativity. Perfect for planning, doodling, and everything in between.</p>
            </div>
        </div>

        <!-- Feature 2: A Style for Every Personality -->
        <div class="flex items-center max-w-lg">
            <img src="Doodly images/like.png" alt="Style" class="w-20 h-20 mr-6">
            <div class="w-full">
                <h3 class="text-2xl font-semibold text-gray-900">A Style for Every Personality</h3>
                <p class="text-gray-600 text-base">Find your vibe with our wide range of cover styles—there’s something for everyone!</p>
            </div>
        </div>

        <!-- Feature 3 (Now Durable, Quality Materials) -->
        <div class="flex items-center max-w-lg">
            <img src="Doodly images/certificate.png" alt="Quality Materials" class="w-20 h-20 mr-6">
            <div class="w-full">
                <h3 class="text-2xl font-semibold text-gray-900">Durable, Quality Materials</h3>
                <p class="text-gray-600 text-base">Made with premium paper that’s smooth to write on and built to last, no matter how often you use it.</p>
            </div>
        </div>
    </div>
</section>


<section class="h-auto flex flex-col items-center py-12 mb-[100px] bg-gradient-to-b from-[#F9F6FF] via-[#EDFAFF] to-[#FFF0EF]">
    <div class="flex w-full max-w-7xl px-6 lg:px-24 flex-col-reverse lg:flex-row items-center">
        <!-- Left side for text content -->
        <div class="flex-1 flex flex-col space-y-6 py-8 text-center lg:text-left">
            <h2 class="text-3xl font-bold text-[#333] leading-tight">
                See How Others Are Using Doodly!
            </h2>
            <p class="text-lg text-gray-700">
                Your journal is more than just pages—it’s a space for dreams, plans, and creativity. 
                Check out how others are using Doodly to stay inspired, organized, and artistic!
            </p>
            <p class="text-lg font-semibold text-[#333]">
                Share yours with <span class="text-blue-500">#DoodlyJournals</span> for a chance to be featured!
            </p>
        </div>

        <!-- Right side for image gallery -->
        <div class="w-full lg:w-1/2 flex flex-col space-y-4">
            <div class="w-full h-64 rounded-lg overflow-hidden shadow-lg">
                <img src="Doodly images/image2.jpg" alt="Image 1" class="w-full h-full object-cover">
            </div>
            <div class="flex space-x-4 w-full h-32">
                <div class="w-1/2 h-full rounded-lg overflow-hidden shadow-lg">
                    <img src="Doodly images/image1.jpg" alt="Image 2" class="w-full h-full object-cover">
                </div>
                <div class="w-1/2 h-full rounded-lg overflow-hidden shadow-lg">
                    <img src="Doodly images/sec3.jpg" alt="Image 3" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-gray-100 h-[200px] flex items-center relative">
    <div class="container mx-auto px-8 md:px-16 w-full flex justify-between items-center">
        
        <!-- Left: Logo -->
        <div>
            <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="w-24">
        </div>

        <!-- Center: Navigation & Contact -->
        <div class="flex space-x-16">
            <!-- Navigation Links -->
            <div class="flex flex-col text-sm text-gray-700 space-y-1">
                <a href="#" class="hover:text-gray-500">Home</a>
                <a href="#" class="hover:text-gray-500">About Us</a>
                <a href="#" class="hover:text-gray-500">Contact Us</a>
                <a href="#" class="hover:text-gray-500">Privacy Policy | Terms of Service</a>
            </div>

            <!-- Contact Info -->
            <div class="flex flex-col text-sm text-gray-700 space-y-1">
                <a href="#" > doodly@gmail.com </a>
                <p>+212 97 58 32 47</p>
                <p>20 Rue Biyara Boukroune</p>
                <a href="#" >Return & Refund Policy</a>
            </div>
        </div>

        <!-- Right: Social Media -->
        <div class="flex flex-col items-center space-y-1">
            <p class="text-sm font-semibold">Follow Us:</p>
            <div class="flex space-x-2">
                <a href="#"><img src="Doodly images/fcb.png" alt="facebook" class="w-5"></a>
                <a href="#"><img src="Doodly images/instagram.png" alt="Instagram" class="w-5"></a>
            </div>
        </div>
    </div>

    <!-- Bottom Right: Copyright -->
    <div class="absolute bottom-4 right-6 text-sm text-gray-700">
        © 2025 Doodly. All Rights Reserved
    </div>
</footer>






    
</html>
