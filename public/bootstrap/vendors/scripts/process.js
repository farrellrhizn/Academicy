var width = 100,
    perfData = window.performance.timing,
    EstimatedTime = -(perfData.loadEventEnd - perfData.navigationStart),
    time = 500; // Tetapkan 500ms (setengah detik)

var PercentageID = $("#percent1"),
    start = 0,
    end = 100,
    duration = 500; // 0.5 detik

animateValue(PercentageID, start, end, duration);

function animateValue(id, start, end, duration) {
    var startTime = performance.now();
    var obj = $(id);
    
    function update() {
        var elapsed = performance.now() - startTime;
        var progress = Math.min(elapsed / duration, 1);
        var current = Math.round(progress * (end - start) + start);
        
        $(obj).text(current + "%");
        $("#bar1").css('width', current + "%");
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    requestAnimationFrame(update);
}

// Fade out setelah animasi selesai
setTimeout(function(){
    $('.pre-loader').fadeOut(1000); // Fade cepat 100ms
}, 600); // Total waktu: 500ms animasi + 100ms buffer
