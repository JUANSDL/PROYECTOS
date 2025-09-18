// Mundiales App - JavaScript
// Aplicación web para explorar la historia de los Mundiales de Fútbol

// ============================
// DATOS DE EJEMPLO
// ============================

const WORLDCUPS = [
  { year: 1930, host: "Uruguay", name: "Uruguay 1930", alias:"Primer Mundial", logo:"https://tse3.mm.bing.net/th/id/OIP.AWXb4i7oyNIP_5vQ0dXabwHaKv?rs=1&pid=ImgDetMain&o=7&rm=3", likes: 120, comments: 48 },
  { year: 1950, host: "Brasil", name: "Brasil 1950", alias:"Maracanazo", logo:"https://tse4.mm.bing.net/th/id/OIP.UEJRBHcdO7LEYS2qtT_g2wHaKw?rs=1&pid=ImgDetMain&o=7&rm=3", likes: 220, comments: 80 },
  { year: 1970, host: "México", name: "México 1970", alias:"La de Pelé", logo:"https://tse4.mm.bing.net/th/id/OIP.EiKXLZ_WZpj9i_RHVKDCqwHaFj?rs=1&pid=ImgDetMain&o=7&rm=3", likes: 540, comments: 210 },
  { year: 1986, host: "México", name: "México 1986", alias:"La Mano de Dios", logo:"https://i.pinimg.com/originals/8a/be/f9/8abef92df7c0c30d6d06dad93a0ae184.png", likes: 610, comments: 260 },
  { year: 1998, host: "Francia", name: "Francia 1998", alias:"Zizou", logo:"https://th.bing.com/th/id/R.6539c3402abb5763c14a1528fb55fcbc?rik=DhZsqPlaE%2b%2fpBg&pid=ImgRaw&r=0", likes: 470, comments: 133 },
  { year: 2002, host: "Corea/Japón", name: "Corea/Japón 2002", alias:"Doble sede", logo:"https://tse3.mm.bing.net/th/id/OIP.5Xn3Gk2AhA9iwim_S53OmwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3", likes: 500, comments: 150 },
  { year: 2010, host: "Sudáfrica", name: "Sudáfrica 2010", alias:"Jabulani", logo:"https://th.bing.com/th/id/R.e16193a6c276b3eaaef36edd6a95e387?rik=WClnl4EvIq3MlA&pid=ImgRaw&r=0", likes: 690, comments: 300 },
  { year: 2014, host: "Brasil", name: "Brasil 2014", alias:"Mineirazo", logo:"https://th.bing.com/th/id/OIP.Pd_VSf7HxJ1mUpRKWuN6VwHaES?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3", likes: 880, comments: 410 },
  { year: 2018, host: "Rusia", name: "Rusia 2018", alias:"VAR", logo:"https://th.bing.com/th/id/R.ff69404a65cc3215d5fb921987059396?rik=VJkPmx6zbsMmcw&riu=http%3a%2f%2f4.bp.blogspot.com%2f-mI8c_hLez24%2fVP3-XTk8dPI%2fAAAAAAAAIkc%2ffpi6SFOhuTM%2fs1600%2flogo%2bmundial%2brussia%2b2018%2bvector%2bai%2b(Large).jpg&ehk=YiFRYv3LpJl1JYD2AO3BhxdOzJraMWtp%2fWSIBFjCIko%3d&risl=&pid=ImgRaw&r=0", likes: 760, comments: 350 },
  { year: 2022, host: "Catar", name: "Catar 2022", alias:"La Scaloneta", logo:"https://s.yimg.com/uu/api/res/1.2/VSIpT7GvCBNhaaxk7ksGvw--~B/aD04NTI7dz0xMjgwO3NtPTE7YXBwaWQ9eXRhY2h5b24-/https://media.zenfs.com/es/lanacion.com.ar/a3d5959cd5fc0ae25fa84a8abdcd665f", likes: 990, comments: 520 },
  { year: 2026, host: "Canadá/EE.UU./México", name: "Mundial 2026", alias:"Trisede", logo:"https://tse2.mm.bing.net/th/id/OIP.tHVF1eZHL0o2yCnpNnVUQAHaE_?rs=1&pid=ImgDetMain&o=7&rm=3", likes: 50, comments: 12 }
];

const MOCK_POSTS = [
  { id:1, title:"Golazo de volea • 1970", worldcup:1970, category:"Jugadas", author:"Ana P.", likes:1200, comments:240, approved:true, media:"img",
    src:"https://tse3.mm.bing.net/th/id/OIP.N5YQYu1L3tvPVi0SgaDDSAHaEK?rs=1&pid=ImgDetMain&o=7&rm=3", description:"Increíble golazo de volea en el Mundial de México 1970" },
  { id:2, title:"Entrevista a Zidane • 1998", worldcup:1998, category:"Entrevistas", author:"Luis R.", likes:640, comments:120, approved:true, media:"vid",
    src:"https://youtu.be/mgPw2ivVZMY", description:"Entrevista exclusiva con Zinedine Zidane tras ganar el Mundial" },
  { id:3, title:"Cánticos en Johannesburgo • 2010", worldcup:2010, category:"Cultura", author:"María G.", likes:980, comments:300, approved:false, media:"img",
    src:"https://th.bing.com/th/id/R.a3bc8630d9e20533d86c8e00ba26bce6?rik=0%2b49DNbvoAsFhg&pid=ImgRaw&r=0", description:"Los increíbles cánticos de los fanáticos en Sudáfrica" },
  { id:4, title:"Polémica del VAR • 2018", worldcup:2018, category:"Polémicas", author:"J. Soto", likes:420, comments:88, approved:true, media:"img",
    src:"https://th.bing.com/th/id/OIP.P3RMd8XIFP8aDjDDJebdgQHaE8?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3", description:"Análisis de las decisiones más polémicas del VAR en Rusia 2018" }
];

const FEATURED_POSTS = [
  {
    id: 1,
    title: "Final Mundial 2014",
    description: "Revive el gol de Gotze en el minuto 113 que le dio a Alemania su cuarto título mundial. Un partido intenso que se decidió en la prórroga con una jugada magistral que pasará a la historia.",
    image: "https://images.unsplash.com/photo-1596510913920-85d87a1800d2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=900&q=80",
    worldcup: "Brasil 2014",
    category: "Partidos",
    author: "Carlos M.",
    date: "13 Jul 2014",
    likes: 1200
  },
  {
    id: 2,
    title: "La Mano de Dios",
    description: "El polémico gol de Maradona que sigue generando debate después de más de 30 años. Una jugada que marcó un antes y un después en la historia de los mundiales y que demostró la astucia del genio argentino.",
    image: "https://images.unsplash.com/photo-1596726832705-4c535151ea0f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=900&q=80",
    worldcup: "México 1986",
    category: "Jugadas",
    author: "Laura G.",
    date: "22 Jun 1986",
    likes: 980
  },
  {
    id: 3,
    title: "El Equipo Perfecto",
    description: "Considerado por muchos el mejor equipo de la historia, con Pelé, Jairzinho, Rivelino y Tostão. Un equipo que mostró un fútbol espectacular y que dejó momentos inolvidables en el Mundial de México 1970.",
    image: "https://images.unsplash.com/photo-1599669454699-248893623464?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=900&q=80",
    worldcup: "México 1970",
    category: "Estadísticas",
    author: "Roberto S.",
    date: "21 Jun 1970",
    likes: 1500
  }
];

// ============================
// UTILIDADES
// ============================

const $ = (sel, root=document) => root.querySelector(sel);
const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));

// ============================
// ESTADO DE LA APLICACIÓN
// ============================

let currentUser = null;

// ============================
// INICIALIZACIÓN
// ============================

document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
});

function initializeApp() {
  setupNavigation();
  setupCountdown();
  setupTimeline();
  setupFilters();
  setupPublications();
  setupModals();
  setupForms();
  setupInteractiveElements();
  renderWorldcups([...WORLDCUPS].sort((a,b) => b.year - a.year));
}

// ============================
// NAVEGACIÓN
// ============================

function setupNavigation() {
  const navToggle = $('#nav-toggle');
  const navLinks = $('#nav-links');
  
  // Toggle menú móvil
  navToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    const icon = navToggle.querySelector('i');
    icon.className = navLinks.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
  });

  // Navegación suave
  $$('[data-scroll]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const target = $(link.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
        
        // Cerrar menú móvil
        navLinks.classList.remove('active');
        $('#nav-toggle i').className = 'fas fa-bars';
        
        // Actualizar estado activo
        $$('.nav__links a').forEach(a => a.classList.remove('active'));
        link.classList.add('active');
      }
    });
  });

  // Actualizar menú activo al hacer scroll
  window.addEventListener('scroll', updateActiveNavItem);
}

function updateActiveNavItem() {
  const sections = $$('section[id]');
  let current = '';
  
  sections.forEach(section => {
    const rect = section.getBoundingClientRect();
    if (rect.top <= 100 && rect.bottom >= 100) {
      current = section.id;
    }
  });
  
  $$('.nav__links a').forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === `#${current}`) {
      link.classList.add('active');
    }
  });
}

// ============================
// CUENTA REGRESIVA
// ============================

function setupCountdown() {
  const targetDate = new Date('2026-06-11T00:00:00').getTime();
  let countdownInterval;
  
  function updateCountdown() {
    const now = new Date().getTime();
    const distance = targetDate - now;
    
    if (distance < 0) {
      $('#countdown').innerHTML = `
        <div style="text-align: center; padding: 2rem;">
          <h3 style="color: var(--primary); font-size: 1.5rem; margin-bottom: 1rem;">
            🎉 ¡El Mundial 2026 ya comenzó! 🎉
          </h3>
          <p style="color: var(--muted);">¡Disfruta de la mayor fiesta del fútbol mundial!</p>
        </div>
      `;
      clearInterval(countdownInterval);
      return;
    }
    
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Actualizar con animación
    animateNumber($('#days'), days);
    animateNumber($('#hours'), hours);
    animateNumber($('#minutes'), minutes);
    animateNumber($('#seconds'), seconds);
    
    // Actualizar progreso
    updateProgress(distance, targetDate);
  }
  
  function animateNumber(element, newValue) {
    const currentValue = parseInt(element.textContent) || 0;
    if (currentValue !== newValue) {
      element.style.transform = 'scale(1.1)';
      element.style.color = 'var(--hot)';
      setTimeout(() => {
        element.textContent = newValue.toString().padStart(2, '0');
        element.style.transform = 'scale(1)';
        element.style.color = 'var(--primary)';
      }, 150);
    }
  }
  
  function updateProgress(distance, targetDate) {
    const totalTime = targetDate - new Date('2022-12-18T00:00:00').getTime(); // Desde el último mundial
    const elapsed = totalTime - distance;
    const progress = Math.min((elapsed / totalTime) * 100, 100);
    
    const progressBar = document.querySelector('.countdown-progress');
    if (progressBar) {
      progressBar.style.width = `${progress}%`;
    }
  }
  
  updateCountdown();
  countdownInterval = setInterval(updateCountdown, 1000);
}

// ============================
// TIMELINE
// ============================

function setupTimeline() {
  const timelineContainer = $('#timeline-container');
  const sortedWorldcups = [...WORLDCUPS].sort((a,b) => a.year - b.year);
  
  const timelineHTML = sortedWorldcups.map((worldcup, index) => `
    <div class="timeline-item" data-year="${worldcup.year}" style="animation-delay: ${index * 0.2}s;">
      <div class="timeline-year">${worldcup.year}</div>
      <div class="timeline-content">
        <div class="timeline-header">
          <h3 class="timeline-title">${worldcup.name}</h3>
          <div class="timeline-stats">
            <span class="stat-item">❤️ ${worldcup.likes}</span>
            <span class="stat-item">💬 ${worldcup.comments}</span>
          </div>
        </div>
        <p class="timeline-location">📍 ${worldcup.host}</p>
        <p class="timeline-description">${worldcup.alias}</p>
        <div class="timeline-actions">
          <button class="btn btn--sm btn--ghost" onclick="showWorldcupDetails(${worldcup.year})">
            Ver detalles
          </button>
          <button class="btn btn--sm btn--primary" onclick="likeWorldcup(${worldcup.year})">
            ❤️ Me gusta
          </button>
        </div>
      </div>
    </div>
  `).join('');
  
  timelineContainer.innerHTML = timelineHTML;
  
  // Agregar animaciones de entrada
  setTimeout(() => {
    $$('.timeline-item').forEach((item, index) => {
      setTimeout(() => {
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
      }, index * 200);
    });
  }, 100);
}

// ============================
// FILTROS
// ============================

function setupFilters() {
  populateFilterOptions();
  
  const form = $('#filters');
  form.addEventListener('submit', applyFilters);
  form.addEventListener('reset', () => setTimeout(applyFilters, 0));
  
  ['search', 'host', 'year', 'sort'].forEach(id => {
    $('#'+id).addEventListener('change', applyFilters);
  });
}

function populateFilterOptions() {
  const hostSelect = $('#host');
  const yearSelect = $('#year');

  const hosts = [...new Set(WORLDCUPS.map(w => w.host))].sort();
  hosts.forEach(h => {
    hostSelect.insertAdjacentHTML('beforeend', `<option value="${h}">${h}</option>`);
  });

  const years = [...new Set(WORLDCUPS.map(w => w.year))].sort((a,b) => b-a);
  years.forEach(y => {
    yearSelect.insertAdjacentHTML('beforeend', `<option value="${y}">${y}</option>`);
  });
}

function applyFilters(e) {
  if (e) e.preventDefault();
  
  const query = $('#search').value.trim().toLowerCase();
  const host = $('#host').value;
  const year = $('#year').value;
  const sort = $('#sort').value;

  let filtered = WORLDCUPS.filter(w => {
    const text = `${w.host} ${w.year} ${w.name} ${w.alias}`.toLowerCase();
    const matchesQuery = !query || text.includes(query);
    const matchesHost = !host || w.host === host;
    const matchesYear = !year || String(w.year) === year;
    return matchesQuery && matchesHost && matchesYear;
  });

  // Ordenar
  switch (sort) {
    case "year-asc": 
      filtered.sort((a,b) => a.year - b.year); 
      break;
    case "host-asc": 
      filtered.sort((a,b) => a.host.localeCompare(b.host)); 
      break;
    case "likes-desc": 
      filtered.sort((a,b) => b.likes - a.likes); 
      break;
    case "comments-desc": 
      filtered.sort((a,b) => b.comments - a.comments); 
      break;
    default: 
      filtered.sort((a,b) => b.year - a.year);
  }

  renderWorldcups(filtered);
}

function renderWorldcups(list) {
  const grid = $('#worldcup-grid');
  grid.innerHTML = '';
  
  if (!list.length) {
    grid.innerHTML = `<p class="muted">No se encontraron mundiales con los filtros aplicados.</p>`;
    return;
  }

  const cards = list.map(w => `
    <article class="card" data-year="${w.year}">
      <div class="card__media">
        <img src="${w.logo}" alt="Afiche ${w.name}" loading="lazy">
      </div>
      <div class="card__body">
        <h3 class="card__title">${w.name}</h3>
        <p class="card__meta">${w.host} • ${w.year}</p>
        <p class="card__desc">${w.alias}</p>
      </div>
      <footer class="card__footer">
        <span>❤️ ${w.likes}</span>
        <span>💬 ${w.comments}</span>
        <button class="btn btn--sm btn--ghost" onclick="showWorldcupDetails(${w.year})">
          Ver más
        </button>
      </footer>
    </article>
  `).join('');
  
  grid.innerHTML = cards;
}

// ============================
// PUBLICACIONES
// ============================

function setupPublications() {
  populateCreatePostForm();
  renderPosts("recent");
  
  // Tabs
  $$('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      $$('.tab').forEach(t => t.classList.remove('is-active'));
      tab.classList.add('is-active');
      renderPosts(tab.dataset.tab);
    });
  });
  
  // Toggle admin
  $('#toggle-admin').addEventListener('change', () => {
    const activeTab = $('.tab.is-active').dataset.tab;
    renderPosts(activeTab);
  });
}

function populateCreatePostForm() {
  const select = $('#new-post-worldcup');
  WORLDCUPS.forEach(w => {
    select.insertAdjacentHTML('beforeend', `<option value="${w.year}">${w.name}</option>`);
  });
}

function renderPosts(mode = "recent") {
  const adminMode = $('#toggle-admin').checked;
  const container = $('#posts');
  container.innerHTML = '';

  let posts = [...MOCK_POSTS];
  if (!adminMode) {
    posts = posts.filter(p => p.approved);
  }

  // Ordenar
  switch (mode) {
    case "likes":
      posts.sort((a,b) => b.likes - a.likes);
      break;
    case "comments":
      posts.sort((a,b) => b.comments - a.comments);
      break;
    default:
      posts.sort((a,b) => b.id - a.id);
  }

  posts.forEach(post => {
    const pendingBadge = adminMode && !post.approved ? 
      `<span style="background: rgba(255,209,102,.2); color: #f9d27b; padding: 0.2rem 0.5rem; border-radius: 8px; font-size: 0.75rem;">Pendiente</span>` : '';
    
    const adminControls = adminMode ? `
      <div style="display: flex; gap: 8px;">
        <button class="btn btn--primary btn--sm" onclick="approvePost(${post.id})">Aprobar</button>
        <button class="btn btn--danger btn--sm" onclick="removePost(${post.id})">Eliminar</button>
      </div>` : '';

    const media = post.media === "img" ? 
      `<img src="${post.src}" alt="${post.title}" loading="lazy">` : 
      `<div style="aspect-ratio: 16/9; display: grid; place-items: center; background: #0c0f14;">
        <a href="${post.src}" target="_blank" style="color: var(--accent); font-weight: 700; text-decoration: none;">Ver video ▶</a>
      </div>`;

    container.insertAdjacentHTML('beforeend', `
      <article class="post" data-id="${post.id}">
        <div class="post__media">${media}</div>
        <div class="post__body">
          <h4>${post.title} ${pendingBadge}</h4>
          <p class="muted">${post.category} • ${post.worldcup} • por ${post.author}</p>
          <p style="margin: 0.5rem 0; line-height: 1.4;">${post.description}</p>
        </div>
        <footer class="post__footer">
          <div style="display: flex; gap: 1rem;">
            <span>❤️ ${post.likes}</span>
            <span>💬 ${post.comments}</span>
          </div>
          ${adminControls}
        </footer>
      </article>
    `);
  });
}

// ============================
// MODALES
// ============================

function setupModals() {
  const authModal = $('#auth-modal');
  const publicationModal = $('#publication-modal');
  
  // Botones para abrir modales
  $('#btn-open-login').addEventListener('click', () => openAuthModal('login'));
  $('#btn-open-register').addEventListener('click', () => openAuthModal('register'));
  $('#btn-open-register-hero').addEventListener('click', () => openAuthModal('register'));
  
  // Cerrar modales
  $('#auth-close').addEventListener('click', () => authModal.close());
  $('#publication-close').addEventListener('click', () => publicationModal.close());
  
  // Tabs en modal de auth
  $('#auth-register-tab').addEventListener('click', () => toggleAuthForm('register'));
  $('#auth-login-tab').addEventListener('click', () => toggleAuthForm('login'));
  
  // Cerrar al hacer click fuera
  [authModal, publicationModal].forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.close();
    });
  });

  // Botones de publicaciones destacadas
  $$('.btn-ticket').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = parseInt(btn.dataset.publication);
      const post = FEATURED_POSTS.find(p => p.id === id);
      if (post) showPublication(post);
    });
  });
}

function openAuthModal(mode) {
  $('#auth-title').textContent = mode === 'login' ? 'Ingresar' : 'Crear cuenta';
  toggleAuthForm(mode);
  $('#auth-modal').showModal();
}

function toggleAuthForm(mode) {
  const isRegister = mode === 'register';
  $('#register-form').classList.toggle('is-hidden', !isRegister);
  $('#login-form').classList.toggle('is-hidden', isRegister);
  $('#auth-register-tab').className = isRegister ? 'btn btn--primary' : 'btn btn--ghost';
  $('#auth-login-tab').className = isRegister ? 'btn btn--ghost' : 'btn btn--primary';
}

function showPublication(post) {
  $('#publication-title').textContent = post.title;
  $('#publication-img').src = post.image;
  $('#publication-description').textContent = post.description;
  $('#publication-worldcup').textContent = post.worldcup;
  $('#publication-category').textContent = post.category;
  $('#publication-author').textContent = post.author;
  $('#publication-date').textContent = post.date;
  $('#publication-modal').showModal();
}

// ============================
// FORMULARIOS
// ============================

function setupForms() {
  $('#btn-register').addEventListener('click', handleRegister);
  $('#btn-login').addEventListener('click', handleLogin);
  $('#create-post-form').addEventListener('submit', handleCreatePost);
  $('#view-individual-post').addEventListener('click', handleViewPost);
}

function handleRegister() {
  const data = {
    name: $('#r-name').value.trim(),
    email: $('#r-email').value.trim(),
    dob: $('#r-dob').value,
    gender: $('#r-gender').value,
    birthCountry: $('#r-birth-country').value.trim(),
    nationality: $('#r-nationality').value.trim(),
    password: $('#r-pass').value
  };

  // Validaciones
  if (!data.name || !data.email || !data.dob || !data.gender || !data.birthCountry || !data.nationality || !data.password) {
    showNotification('Completa todos los campos requeridos', 'error');
    return;
  }

  const age = getAge(data.dob);
  if (age < 12) {
    showNotification('Debes tener al menos 12 años para registrarte', 'error');
    return;
  }

  if (data.password.length < 6) {
    showNotification('La contraseña debe tener al menos 6 caracteres', 'error');
    return;
  }

  // Simular registro
  const users = JSON.parse(localStorage.getItem('users') || '[]');
  if (users.some(u => u.email === data.email)) {
    showNotification('Ya existe una cuenta con ese correo', 'error');
    return;
  }

  users.push({...data, createdAt: Date.now()});
  localStorage.setItem('users', JSON.stringify(users));
  
  showNotification('Cuenta creada exitosamente', 'success');
  toggleAuthForm('login');
}

function handleLogin() {
  const email = $('#l-email').value.trim();
  const password = $('#l-pass').value;

  if (!email || !password) {
    showNotification('Ingresa correo y contraseña', 'error');
    return;
  }

  const users = JSON.parse(localStorage.getItem('users') || '[]');
  const user = users.find(u => u.email === email);
  
  if (user) {
    currentUser = user;
    $('#auth-modal').close();
    updateUserInterface();
    showNotification('Sesión iniciada correctamente', 'success');
  } else {
    showNotification('Usuario no encontrado. Regístrate primero.', 'error');
  }
}

function handleCreatePost(e) {
  e.preventDefault();
  
  const data = {
    title: $('#new-post-title').value.trim(),
    category: $('#new-post-category').value,
    worldcup: parseInt($('#new-post-worldcup').value),
    author: $('#new-post-author').value.trim(),
    description: $('#new-post-description').value.trim(),
    media: $('#new-post-media').value.trim()
  };

  if (!data.title || !data.category || !data.worldcup || !data.author || !data.description) {
    showNotification('Completa todos los campos obligatorios', 'error');
    return;
  }

  const newPost = {
    id: Math.max(...MOCK_POSTS.map(p => p.id)) + 1,
    title: data.title,
    worldcup: data.worldcup,
    category: data.category,
    author: data.author,
    description: data.description,
    likes: 0,
    comments: 0,
    approved: false,
    media: "img",
    src: data.media || "https://images.unsplash.com/photo-1543326727-cf6c39cd2f98?q=80&w=1200&auto=format&fit=crop"
  };

  MOCK_POSTS.unshift(newPost);
  $('#create-post-form').reset();
  
  showNotification('Publicación creada. Pendiente de aprobación.', 'success');
  
  if ($('#toggle-admin').checked) {
    renderPosts($('.tab.is-active').dataset.tab);
  }
}

function handleViewPost() {
  const postId = parseInt($('#post-id-input').value);
  if (!postId) {
    showNotification('Ingresa un ID válido', 'error');
    return;
  }

  const post = MOCK_POSTS.find(p => p.id === postId);
  if (!post) {
    showNotification(`No se encontró publicación con ID ${postId}`, 'error');
    return;
  }

  const worldcup = WORLDCUPS.find(w => w.year === post.worldcup);
  showPublication({
    id: post.id,
    title: post.title,
    description: post.description,
    image: post.src,
    worldcup: worldcup ? worldcup.name : post.worldcup,
    category: post.category,
    author: post.author,
    date: new Date().toLocaleDateString()
  });
}

// ============================
// ELEMENTOS INTERACTIVOS
// ============================

function setupInteractiveElements() {
  // Estadios clickeables
  $('[data-stadium]').forEach(card => {
    card.addEventListener('click', () => {
      const stadium = card.dataset.stadium;
      showNotification(`Información detallada de ${card.querySelector('.stadium-name').textContent}`, 'success');
    });
  });

  // Jugadores clickeables
  $('[data-player]').forEach(card => {
    card.addEventListener('click', () => {
      const player = card.dataset.player;
      showNotification(`Biografía completa de ${card.querySelector('.player-name').textContent}`, 'success');
    });
  });

  // Botones de interacción en publicación
  $('#like-btn').addEventListener('click', () => {
    showNotification('¡Te gusta esta publicación!', 'success');
  });

  $('#comment-btn').addEventListener('click', () => {
    showNotification('Función de comentarios próximamente', 'success');
  });

  $('#share-btn').addEventListener('click', () => {
    if (navigator.share) {
      navigator.share({
        title: 'Mundiales - Publicación',
        text: 'Mira esta increíble publicación sobre mundiales',
        url: window.location.href
      });
    } else {
      showNotification('Enlace copiado al portapapeles', 'success');
    }
  });
}

// ============================
// FUNCIONES AUXILIARES
// ============================

function getAge(dateString) {
  const birthday = new Date(dateString);
  const today = new Date();
  let age = today.getFullYear() - birthday.getFullYear();
  const monthDiff = today.getMonth() - birthday.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
    age--;
  }
  return age;
}

function updateUserInterface() {
  if (currentUser) {
    $('.nav__actions').innerHTML = `
      <span style="padding: 0.4rem 0.6rem; background: #1a1c24; border-radius: 999px; border: 1px solid rgba(255,255,255,.06);">
        👋 ${currentUser.name.split(' ')[0]}
      </span>
      <button class="btn btn--ghost" onclick="logout()">Salir</button>
    `;
  }
}

function showNotification(message, type = 'success') {
  const notification = $('#notification');
  notification.textContent = message;
  notification.className = `notification ${type} show`;
  
  setTimeout(() => {
    notification.classList.remove('show');
  }, 3000);
}

// ============================
// FUNCIONES GLOBALES
// ============================

window.showWorldcupDetails = function(year) {
  const worldcup = WORLDCUPS.find(w => w.year === year);
  if (worldcup) {
    showNotification(`Información detallada de ${worldcup.name}`, 'success');
  }
};

window.likeWorldcup = function(year) {
  const worldcup = WORLDCUPS.find(w => w.year === year);
  if (worldcup) {
    worldcup.likes++;
    showNotification(`¡Te gusta ${worldcup.name}! ❤️`, 'success');
    
    // Actualizar la línea del tiempo
    setupTimeline();
    
    // Actualizar la grilla de mundiales
    renderWorldcups([...WORLDCUPS].sort((a,b) => b.year - a.year));
  }
};

window.approvePost = function(id) {
  const post = MOCK_POSTS.find(p => p.id === id);
  if (post) {
    post.approved = true;
    renderPosts($('.tab.is-active').dataset.tab);
    showNotification('Publicación aprobada', 'success');
  }
};

window.removePost = function(id) {
  const index = MOCK_POSTS.findIndex(p => p.id === id);
  if (index >= 0) {
    MOCK_POSTS.splice(index, 1);
    renderPosts($('.tab.is-active').dataset.tab);
    showNotification('Publicación eliminada', 'success');
  }
};

window.logout = function() {
  currentUser = null;
  localStorage.removeItem('currentUser');
  location.reload();
};