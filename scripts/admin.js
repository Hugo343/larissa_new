document.addEventListener("DOMContentLoaded", () => {
    // Add any JavaScript functionality for the admin panel here
  
    // Example: Confirm deletion
    const deleteButtons = document.querySelectorAll(".btn-danger")
    deleteButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        if (!confirm("Are you sure you want to delete this item?")) {
          e.preventDefault()
        }
      })
    })
  
    // Example: Toggle sidebar on mobile
    const menuToggle = document.querySelector(".menu-toggle")
    const adminNav = document.querySelector(".admin-nav")
  
    if (menuToggle && adminNav) {
      menuToggle.addEventListener("click", () => {
        adminNav.classList.toggle("active")
      })
    }
  
    // Example: Highlight active section in sidebar
    const currentPath = window.location.search
    const sidebarLinks = document.querySelectorAll(".admin-nav a")
  
    sidebarLinks.forEach((link) => {
      if (link.getAttribute("href") === currentPath) {
        link.classList.add("active")
      }
    })
  })
  
  