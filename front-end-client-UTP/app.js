const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
    headers: { 'Content-Type': 'application/json' }
});

getContainers();

function clearErrors() {
    document.getElementById('err-container_id').innerText = '';
    document.getElementById('err-waste_type').innerText = '';
    document.getElementById('err-weight_kg').innerText = '';
}

function getContainers() {
    api.get('/containers').then(res => {
        const containers = res.data.data;
        let totalWeight = 0;
        let html = '';

        containers.forEach(c => {
            totalWeight += parseInt(c.weight_kg);
            let statusClass = c.status === 'Active' ? 'badge-active' : 'badge-archived';
            
            html += `
                <tr>
                    <td><strong>${c.container_id}</strong></td>
                    <td>${c.waste_type}</td>
                    <td>${c.weight_kg}</td>
                    <td class="${statusClass}">${c.status}</td>
                    <td class="action-btns">
                        <button onclick="archiveContainer('${c.container_id}')">Archive</button>
                        <button onclick="deleteContainer('${c.container_id}')">Hapus</button>
                    </td>
                </tr>
            `;
        });

        document.getElementById('table-body').innerHTML = html;
        document.getElementById('total-weight').innerText = totalWeight;
    }).catch(err => console.error(err));
}

function createContainer() {
    clearErrors();
    const payload = {
        container_id: document.getElementById('container_id').value,
        waste_type: document.getElementById('waste_type').value,
        weight_kg: document.getElementById('weight_kg').value,
        status: "Active"
    };

    api.post('/containers', payload).then(res => {
        alert("Berhasil disimpan!");
        getContainers(); // Refresh list
    }).catch(err => {
        if (err.response && err.response.status === 422) {
            const errors = err.response.data.errors;
            if (errors.container_id) document.getElementById('err-container_id').innerText = errors.container_id[0];
            if (errors.waste_type) document.getElementById('err-waste_type').innerText = errors.waste_type[0];
            if (errors.weight_kg) document.getElementById('err-weight_kg').innerText = errors.weight_kg[0];
        } else {
            alert("Terjadi kesalahan server!");
        }
    });
}

function archiveContainer(id) {
    api.patch(`/containers/${id}/archive`).then(res => {
        alert(res.data.message);
    }).catch(err => console.error(err));
}

function deleteContainer(id) {
    if (confirm(`Yakin ingin menghapus kontainer ${id}?`)) {
        api.delete(`/containers/${id}`).then(res => {
            alert(res.data.message);
        }).catch(err => console.error(err));
    }
}