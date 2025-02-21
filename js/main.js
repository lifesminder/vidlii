console.log("TBA");

if(document.getElementById("subscribe") != null) {
    document.getElementById("subscribe").addEventListener("click", function(e) {
        e.preventDefault();
        alert("Subscribe v2");
    });
}

function report(e, user, what) {
    fetch(`/api/report?to=${user}&what=${what}`).then(resp => { return resp.json() }).then(data => {
        let color = e.parentElement.style.color, content = e.parentElement.innerHTML;
        if(data.status == -2) {
            window.location.href = `/login?next=/user/${user}#report`;
        } else {
            e.parentElement.style.color = (data.status == 0) ? "green" : "red";
            e.parentElement.innerText = data.message;
        }
    });
}