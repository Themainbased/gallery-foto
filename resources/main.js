function validateForm() {
    var albumid = document.getElementById("albumid").value;
    if (albumid == "") {
        alert("Silakan pilih album!");
        return false;
    }
    return true;
}
function confirmDelete(fotoid) {
        var confirmation = confirm("Apakah Anda yakin ingin menghapus foto ini?");
        if (confirmation) {
            window.location.href = "hapus_foto.php?fotoid=" + fotoid;
        }
    }