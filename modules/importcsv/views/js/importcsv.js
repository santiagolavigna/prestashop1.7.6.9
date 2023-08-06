function showLoading() {
    let form = $('#importForm');
    let alert = $('.alert');
    let spinner = $('.spinner-container');

    form.addClass('d-none');
    alert.addClass('d-none');
    spinner.removeClass('d-none');
    spinner.addClass('d-flex');
}

$(document).ready(function() {
    $("#fileInput").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        var label = $(this).siblings(".custom-file-label");
        label.text(fileName || "Choose files...");
    });
});