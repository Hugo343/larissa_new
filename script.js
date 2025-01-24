$(document).ready(function() {
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });

    // Mobile menu toggle
    $('.menu-toggle').on('click', function() {
        $('.nav-links').toggleClass('active');
    });

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 1000);
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggle = document.querySelector(".dropdown-toggle")
    const dropdownMenu = document.querySelector(".dropdown-menu")
  
    if (dropdownToggle && dropdownMenu) {
      dropdownToggle.addEventListener("click", (e) => {
        e.preventDefault()
        dropdownMenu.classList.toggle("show")
      })
  
      // Close the dropdown when clicking outside
      document.addEventListener("click", (e) => {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
          dropdownMenu.classList.remove("show")
        }
      })
    }
  })
  
  