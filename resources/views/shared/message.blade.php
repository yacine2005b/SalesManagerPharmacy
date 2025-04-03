@if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex justify-around" role="alert" id="Msg">
    {{ session('success') }}
   
</div>
@endif


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Automatically hide the message after 3 seconds
    setTimeout(function() {
        const msg = document.querySelector("#Msg");
        if (msg) {
            msg.style.display = 'none';
        }
    }, 2000);

    
    
});
</script>