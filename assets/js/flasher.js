const flashCRUD = document.getElementById("flash-crud");
    if (flashCRUD != null) {
      const FlashType = flashCRUD.getAttribute("data-flashType");
      const Title = flashCRUD.getAttribute("data-title");
      const Text = flashCRUD.getAttribute("data-text");
      const Icon = flashCRUD.getAttribute("data-icon");

      if (FlashType == 1) {
        Swal.fire({
          icon: Icon,
          text: Text,
          title: Title
        });
      } else if (FlashType == 2) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        Toast.fire({
          icon: Icon,
          title: Text
        })
      }


    }