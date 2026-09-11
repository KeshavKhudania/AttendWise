<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.aa-page{background:radial-gradient(circle at top right,#F1F5F9 0%,#F8FAFC 60%,#EEF2F6 100%);padding:2rem 1.5rem 4rem;font-family:'Inter',sans-serif;min-height:100vh;color:#0F172A}
.aa-header{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:2rem;border-bottom:1px solid #E2E8F0;padding-bottom:1.5rem}
.aa-title{font-size:2rem;font-weight:800;letter-spacing:-0.025em;background:linear-gradient(90deg,#0F172A,#334155);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:.25rem}
.aa-subtitle{font-size:.95rem;color:#64748B;font-weight:500}
.aa-card{background:#fff;border-radius:16px;border:1px solid #E2E8F0;box-shadow:0 10px 25px -5px rgba(15,23,42,.04);padding:1.5rem;transition:box-shadow .3s}
.aa-card:hover{box-shadow:0 15px 30px -5px rgba(15,23,42,.07)}
.aa-card-title{font-size:1.05rem;font-weight:700;color:#0F172A;display:flex;align-items:center;gap:.5rem;margin-bottom:1rem}
.aa-card-title i{color:#2563EB}

/* Filter Panel */
.aa-filter-panel{background:linear-gradient(135deg,#FFFFFF 0%,#F8FAFC 100%);border-radius:16px;border:1px solid #E2E8F0;padding:1.5rem;margin-bottom:2rem;box-shadow:0 4px 12px rgba(15,23,42,.03)}
.aa-filter-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem}
.aa-filter-group label{font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem;display:block}
.aa-filter-group select,.aa-filter-group input{width:100%;padding:.55rem .75rem;border:1px solid #E2E8F0;border-radius:10px;font-size:.875rem;font-family:'Inter',sans-serif;color:#334155;background:#fff;transition:all .2s}
.aa-filter-group select:focus,.aa-filter-group input:focus{outline:none;border-color:#3B82F6;box-shadow:0 0 0 3px rgba(59,130,246,.12)}
.aa-filter-actions{display:flex;gap:.75rem;align-items:flex-end;margin-top:1rem}
.aa-btn{padding:.6rem 1.25rem;border-radius:10px;font-weight:600;font-size:.875rem;border:1px solid #CBD5E1;background:#fff;color:#334155;cursor:pointer;display:inline-flex;align-items:center;gap:.5rem;transition:all .25s}
.aa-btn:hover{background:#F8FAFC;transform:translateY(-1px)}
.aa-btn-primary{background:linear-gradient(135deg,#2563EB,#1D4ED8);border-color:#2563EB;color:#fff;box-shadow:0 4px 12px rgba(37,99,235,.25)}
.aa-btn-primary:hover{background:linear-gradient(135deg,#1D4ED8,#1E40AF);color:#fff}
.aa-btn-danger{border-color:#FCA5A5;color:#DC2626;background:#FEF2F2}
.aa-btn-danger:hover{background:#FEE2E2}

/* KPI Cards */
.aa-kpi{position:relative;overflow:hidden;padding:1.25rem;border-radius:14px;background:#fff;border:1px solid #E2E8F0;transition:all .3s cubic-bezier(.4,0,.2,1)}
.aa-kpi:hover{transform:translateY(-3px);box-shadow:0 12px 20px rgba(15,23,42,.06)}
.aa-kpi::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px}
.aa-kpi.kpi-blue::before{background:#3B82F6}.aa-kpi.kpi-green::before{background:#10B981}
.aa-kpi.kpi-red::before{background:#EF4444}.aa-kpi.kpi-amber::before{background:#F59E0B}
.aa-kpi.kpi-purple::before{background:#8B5CF6}.aa-kpi.kpi-teal::before{background:#14B8A6}
.aa-kpi-label{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem}
.aa-kpi-value{font-size:2rem;font-weight:800;color:#0F172A;line-height:1}
.aa-kpi-icon{position:absolute;right:-5px;bottom:-15px;font-size:5rem;color:#F1F5F9;transform:rotate(-10deg)}

/* Charts */
.aa-chart-wrap{position:relative;height:320px;width:100%}

/* Table */
.aa-table{width:100%;border-collapse:separate;border-spacing:0}
.aa-table th{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;padding:.85rem 1rem;border-bottom:2px solid #E2E8F0;background:#F8FAFC;position:sticky;top:0;z-index:2}
.aa-table td{padding:.75rem 1rem;font-size:.85rem;color:#334155;border-bottom:1px solid #F1F5F9}
.aa-table tbody tr:hover td{background:#F8FAFC}
.aa-status{padding:.25rem .65rem;border-radius:9999px;font-size:.7rem;font-weight:700;text-transform:uppercase}
.aa-status.present{background:#DCFCE7;color:#15803D}.aa-status.absent{background:#FEE2E2;color:#DC2626}
.aa-status.late{background:#FEF3C7;color:#B45309}.aa-status.excused{background:#DBEAFE;color:#1D4ED8}

/* Bar breakdown */
.aa-bar-row{display:flex;align-items:center;gap:.75rem;padding:.5rem 0;border-bottom:1px solid #F8FAFC}
.aa-bar-name{width:120px;font-size:.8rem;font-weight:600;color:#334155;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.aa-bar-track{flex:1;height:24px;background:#F1F5F9;border-radius:6px;overflow:hidden;position:relative}
.aa-bar-fill{height:100%;border-radius:6px;transition:width .6s cubic-bezier(.4,0,.2,1);display:flex;align-items:center;padding-left:8px;font-size:.7rem;font-weight:700;color:#fff;min-width:30px}
.aa-bar-pct{width:50px;text-align:right;font-size:.8rem;font-weight:700;color:#0F172A}

/* Pagination */
.aa-pagination{display:flex;justify-content:center;gap:.5rem;margin-top:1rem}
.aa-pagination button{padding:.4rem .8rem;border:1px solid #E2E8F0;border-radius:8px;background:#fff;color:#334155;font-size:.8rem;font-weight:600;cursor:pointer}
.aa-pagination button.active{background:#2563EB;color:#fff;border-color:#2563EB}
.aa-pagination button:disabled{opacity:.4;cursor:not-allowed}

/* Loading */
.aa-loading{display:flex;align-items:center;justify-content:center;padding:3rem;color:#94A3B8;gap:.75rem;font-weight:600}
.aa-spin{animation:aaSpin 1s linear infinite}
@keyframes aaSpin{to{transform:rotate(360deg)}}

/* DOW Heatmap */
.aa-dow-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:.5rem}
.aa-dow-cell{text-align:center;padding:.75rem .5rem;border-radius:10px;transition:all .3s}
.aa-dow-day{font-size:.7rem;font-weight:700;color:#64748B;text-transform:uppercase;margin-bottom:.25rem}
.aa-dow-val{font-size:1.1rem;font-weight:800}

/* Responsive */
@media(max-width:768px){
.aa-filter-grid{grid-template-columns:1fr 1fr}
.aa-header{flex-direction:column;align-items:flex-start}
.aa-dow-grid{grid-template-columns:repeat(4,1fr)}
}
</style>
