document.getElementById('visitorForm').addEventListener('submit', function(event) {
    var date = document.getElementById('date').value;
    var name = document.getElementById('name').value;
    var building = document.getElementById('building').value;
    var purpose = document.getElementById('purpose').value;
    var phone = document.getElementById('phone').value;
    var checkin = document.getElementById('checkin').value;
    var checkout = document.getElementById('checkout').value;

    if (!date || !name || !building || !purpose || !phone || !checkin || !checkout) {
        alert('Please fill out all fields');
        event.preventDefault();
    }
});

function searchVisitor(query) {
    if (query.length < 2) {
        document.getElementById('suggestions').innerHTML = '';
        return;
    }
    
    fetch('search_visitor.php?query=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            let suggestions = '';
            if (data.length > 0) {
                suggestions = '<ul>';
                data.forEach(visitor => {
                    suggestions += `<li onclick="selectVisitor('${visitor.name}', '${visitor.vehicle_number}', '${visitor.contact_number}')">${visitor.name}</li>`;
                });
                suggestions += '</ul>';
            }
            document.getElementById('suggestions').innerHTML = suggestions;
        });
}

function selectVisitor(name, vehicle_number, contact_number) {
    document.getElementById('name').value = name;
    document.getElementById('vehicle_number').value = vehicle_number;
    document.getElementById('phone').value = contact_number;
    document.getElementById('suggestions').innerHTML = '';
}

