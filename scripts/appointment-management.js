document.addEventListener("DOMContentLoaded", () => {
    // Get the modal
    var modal = document.getElementById("editModal")
  
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0]
  
    // Function to open the modal
    window.openModal = (appointmentId) => {
      modal.style.display = "block"
      document.getElementById("modalAppointmentId").value = appointmentId
    }
  
    // When the user clicks on <span> (x), close the modal
    span.onclick = () => {
      modal.style.display = "none"
    }
  
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = (event) => {
      if (event.target == modal) {
        modal.style.display = "none"
      }
    }
  
    // Set minimum date to today for the date input
    var today = new Date().toISOString().split("T")[0]
    document.getElementById("new_date").setAttribute("min", today)
  
    // Handle form submissions
    document.querySelectorAll("form").forEach((form) => {
      form.addEventListener("submit", function (e) {
        e.preventDefault()
  
        // You can add AJAX submission here for a smoother experience
        // For now, we'll just submit the form normally
        this.submit()
      })
    })
  
    // Confirm before cancelling an appointment
    document.querySelectorAll(".cancel-form").forEach((form) => {
      form.addEventListener("submit", (e) => {
        if (!confirm("Are you sure you want to cancel this appointment?")) {
          e.preventDefault()
        }
      })
    })
  
    // Add animation to success and error messages
    const alerts = document.querySelectorAll(".alert")
    alerts.forEach((alert) => {
      alert.style.opacity = "1"
      setTimeout(() => {
        alert.style.opacity = "0"
        setTimeout(() => {
          alert.remove()
        }, 500)
      }, 5000)
    })
  })
  
  