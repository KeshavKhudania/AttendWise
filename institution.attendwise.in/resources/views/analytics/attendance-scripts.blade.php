<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const API_URL = "{{ route('institution.analytics.attendance.api') }}";
let trendChart = null, subjectChart = null;
let currentPage = 1;

function getFilters() {
    const f = {};
    ['department_id','course_id','section_id','subject_id','faculty_id','semester','status','date_from','date_to','day_of_week','student_search'].forEach(k => {
        const el = document.getElementById('f_'+k);
        if (el && el.value) f[k] = el.value;
    });
    f.page = currentPage;
    return f;
}

function fetchData() {
    const params = new URLSearchParams(getFilters());
    document.getElementById('aa-loading').style.display = 'flex';
    document.getElementById('aa-content').style.opacity = '0.4';
    
    fetch(API_URL + '?' + params.toString())
        .then(r => r.json())
        .then(data => {
            renderKPIs(data.summary);
            renderTrendChart(data.daily_trend);
            renderSubjectBreakdown(data.subject_breakdown);
            renderFacultyBreakdown(data.faculty_breakdown);
            renderDOW(data.dow_heatmap);
            renderTable(data.records);
            renderPagination(data.pagination);
        })
        .catch(e => console.error('Analytics fetch error:', e))
        .finally(() => {
            document.getElementById('aa-loading').style.display = 'none';
            document.getElementById('aa-content').style.opacity = '1';
        });
}

function renderKPIs(s) {
    document.getElementById('kpi-total').textContent = s.total.toLocaleString();
    document.getElementById('kpi-present').textContent = s.present.toLocaleString();
    document.getElementById('kpi-absent').textContent = s.absent.toLocaleString();
    document.getElementById('kpi-late').textContent = s.late.toLocaleString();
    document.getElementById('kpi-excused').textContent = s.excused.toLocaleString();
    document.getElementById('kpi-pct').textContent = s.overall_pct + '%';
}

function renderTrendChart(trend) {
    const ctx = document.getElementById('trendCanvas').getContext('2d');
    if (trendChart) trendChart.destroy();
    const grad = ctx.createLinearGradient(0,0,0,320);
    grad.addColorStop(0,'rgba(59,130,246,0.3)');
    grad.addColorStop(1,'rgba(59,130,246,0)');
    trendChart = new Chart(ctx, {
        type:'line',
        data:{
            labels: trend.map(t=>t.label),
            datasets:[{
                label:'Attendance %',
                data: trend.map(t=>t.pct),
                borderColor:'#2563EB', backgroundColor:grad,
                borderWidth:2.5, tension:.4, fill:true,
                pointBackgroundColor:'#fff', pointBorderColor:'#2563EB',
                pointBorderWidth:2, pointRadius:3, pointHoverRadius:5
            },{
                type:'bar', label:'Total Records',
                data: trend.map(t=>t.total),
                backgroundColor:'rgba(148,163,184,0.15)',
                borderRadius:4, barPercentage:.6, yAxisID:'y1'
            }]
        },
        options:{
            maintainAspectRatio:false,
            plugins:{legend:{position:'top',align:'end',labels:{usePointStyle:true,boxWidth:8,font:{family:'Inter',size:11,weight:'600'},color:'#475569'}},
            tooltip:{mode:'index',intersect:false,backgroundColor:'rgba(15,23,42,.9)',padding:10,cornerRadius:8,titleFont:{family:'Inter',size:12,weight:'bold'},bodyFont:{family:'Inter',size:11}}},
            scales:{
                y:{title:{display:true,text:'Attendance %',font:{family:'Inter',size:10,weight:'bold'},color:'#64748B'},min:0,max:100,grid:{color:'#F1F5F9',borderDash:[4,4]},ticks:{font:{family:'Inter',size:10},color:'#64748B'}},
                y1:{display:false,position:'right',grid:{drawOnChartArea:false}},
                x:{grid:{display:false},ticks:{font:{family:'Inter',size:10},color:'#64748B'}}
            }
        }
    });
}

function renderSubjectBreakdown(subjects) {
    const ctx = document.getElementById('subjectCanvas').getContext('2d');
    if (subjectChart) subjectChart.destroy();
    const colors = ['#3B82F6','#10B981','#F59E0B','#8B5CF6','#EC4899','#14B8A6','#F97316','#6366F1','#84CC16','#06B6D4','#E11D48','#A855F7','#0EA5E9','#D946EF','#65A30D'];
    subjectChart = new Chart(ctx, {
        type:'doughnut',
        data:{
            labels: subjects.map(s=>s.code||s.name),
            datasets:[{data:subjects.map(s=>s.total),backgroundColor:colors.slice(0,subjects.length),borderColor:'#fff',borderWidth:2,hoverOffset:6}]
        },
        options:{
            maintainAspectRatio:false, cutout:'65%',
            plugins:{legend:{position:'bottom',labels:{usePointStyle:true,padding:12,font:{family:'Inter',size:10,weight:'600'},color:'#475569'}},
            tooltip:{backgroundColor:'rgba(15,23,42,.9)',padding:10,cornerRadius:8}}
        }
    });
    // Bar list
    let html = '';
    subjects.forEach(s => {
        const color = s.pct >= 75 ? '#10B981' : s.pct >= 50 ? '#F59E0B' : '#EF4444';
        html += `<div class="aa-bar-row"><div class="aa-bar-name" title="${s.name}">${s.code||s.name}</div><div class="aa-bar-track"><div class="aa-bar-fill" style="width:${Math.max(s.pct,5)}%;background:${color}">${s.present}</div></div><div class="aa-bar-pct">${s.pct}%</div></div>`;
    });
    document.getElementById('subjectBars').innerHTML = html || '<p class="text-muted text-center py-3">No subject data</p>';
}

function renderFacultyBreakdown(faculty) {
    let html = '';
    faculty.forEach(f => {
        const color = f.pct >= 75 ? '#10B981' : f.pct >= 50 ? '#F59E0B' : '#EF4444';
        html += `<div class="aa-bar-row"><div class="aa-bar-name" title="${f.name}">${f.name}</div><div class="aa-bar-track"><div class="aa-bar-fill" style="width:${Math.max(f.pct,5)}%;background:${color}">${f.present}/${f.total}</div></div><div class="aa-bar-pct">${f.pct}%</div></div>`;
    });
    document.getElementById('facultyBars').innerHTML = html || '<p class="text-muted text-center py-3">No faculty data</p>';
}

function renderDOW(dow) {
    let html = '';
    dow.forEach(d => {
        const bg = d.pct >= 75 ? 'rgba(16,185,129,.12)' : d.pct >= 50 ? 'rgba(245,158,11,.12)' : d.pct > 0 ? 'rgba(239,68,68,.12)' : 'rgba(148,163,184,.06)';
        const clr = d.pct >= 75 ? '#059669' : d.pct >= 50 ? '#D97706' : d.pct > 0 ? '#DC2626' : '#94A3B8';
        html += `<div class="aa-dow-cell" style="background:${bg}"><div class="aa-dow-day">${d.day}</div><div class="aa-dow-val" style="color:${clr}">${d.pct}%</div><div style="font-size:.65rem;color:#94A3B8;margin-top:2px">${d.total} rec</div></div>`;
    });
    document.getElementById('dowGrid').innerHTML = html || '<p class="text-muted text-center py-3 w-100">No data</p>';
}

function renderTable(records) {
    let html = '';
    records.forEach(r => {
        html += `<tr>
            <td><strong>${r.student_name||'—'}</strong><div style="font-size:.7rem;color:#94A3B8">${r.roll_number||''}</div></td>
            <td>${r.date ? new Date(r.date).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}) : '—'}</td>
            <td>${r.subject_name||'—'}<div style="font-size:.7rem;color:#94A3B8">${r.subject_code||''}</div></td>
            <td>${r.faculty_name||'—'}</td>
            <td>${r.section_name||'—'}</td>
            <td>${r.course_name||'—'}</td>
            <td><span class="aa-status ${r.status}">${r.status}</span></td>
            <td style="font-size:.75rem;color:#94A3B8">${r.start_time&&r.end_time ? r.start_time+' - '+r.end_time : '—'}</td>
        </tr>`;
    });
    document.getElementById('recordsBody').innerHTML = html || '<tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>No records match your filters</td></tr>';
}

function renderPagination(p) {
    let html = `<button ${p.page<=1?'disabled':''} onclick="goPage(${p.page-1})"><i class="fas fa-chevron-left"></i></button>`;
    const maxPages = Math.min(p.total_pages, 7);
    const start = Math.max(1, p.page - 3);
    const end = Math.min(p.total_pages, start + maxPages - 1);
    for (let i = start; i <= end; i++) {
        html += `<button class="${i===p.page?'active':''}" onclick="goPage(${i})">${i}</button>`;
    }
    html += `<button ${p.page>=p.total_pages?'disabled':''} onclick="goPage(${p.page+1})"><i class="fas fa-chevron-right"></i></button>`;
    html += `<span style="font-size:.75rem;color:#94A3B8;margin-left:.5rem">Page ${p.page} of ${p.total_pages} (${p.total} records)</span>`;
    document.getElementById('paginationWrap').innerHTML = html;
}

function goPage(n) { currentPage = n; fetchData(); }

function applyFilters() { currentPage = 1; fetchData(); }

function resetFilters() {
    document.querySelectorAll('.aa-filter-group select, .aa-filter-group input').forEach(el => el.value = '');
    currentPage = 1;
    fetchData();
}

function exportCSV() {
    const params = new URLSearchParams(getFilters());
    params.set('per_page', 99999);
    params.set('page', 1);
    fetch(API_URL + '?' + params.toString())
        .then(r=>r.json())
        .then(data => {
            if (!data.records.length) return alert('No data to export');
            const headers = ['Student','Roll','Date','Subject','Faculty','Section','Course','Status','Time'];
            let csv = headers.join(',') + '\n';
            data.records.forEach(r => {
                csv += [r.student_name,r.roll_number,r.date,r.subject_name,r.faculty_name,r.section_name,r.course_name,r.status,(r.start_time||'')+'-'+(r.end_time||'')].map(v=>'"'+(v||'').replace(/"/g,'""')+'"').join(',') + '\n';
            });
            const blob = new Blob([csv], {type:'text/csv'});
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'attendance_analytics_'+new Date().toISOString().slice(0,10)+'.csv';
            a.click();
        });
}

document.addEventListener('DOMContentLoaded', fetchData);
</script>
