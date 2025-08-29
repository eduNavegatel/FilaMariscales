<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Socio - Filá Mariscales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #dc143c;
            --primary-dark: #8b0000;
            --secondary: #f8f9fa;
            --accent: #ffc107;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Top Navigation Bar */
        .top-navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            color: white !important;
        }
        
        .user-welcome {
            color: white;
            font-weight: 600;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 2px solid var(--primary);
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 13px 13px 0 0;
            border-bottom: none;
        }
        
        .card-title-custom {
            font-family: 'Cinzel', serif;
            font-size: 1.3rem;
            margin: 0;
        }
        
        .card-body-custom {
            padding: 2rem;
        }
        
        /* Profile Section */
        .profile-section {
            text-align: center;
        }
        
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid var(--primary);
            object-fit: cover;
            margin: 0 auto 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .upload-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .upload-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        /* Action Buttons */
        .action-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0.5rem;
            min-width: 200px;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(220, 20, 60, 0.3);
        }
        
        .action-btn i {
            margin-right: 0.5rem;
        }
        
        /* Friends Section */
        .friends-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .friend-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .friend-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .friend-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 0.5rem;
        }
        
        .friend-name {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }
        
        .friend-email {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        /* Comments Section */
        .comment-form {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .comment-input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            resize: vertical;
            min-height: 100px;
        }
        
        .comment-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }
        
        .comments-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .comment-item {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--primary);
        }
        
        .comment-date {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .comment-text {
            color: #495057;
            line-height: 1.5;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .friends-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .action-btn {
                min-width: 100%;
                margin: 0.25rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg top-navbar">
        <div class="container">
            <a class="navbar-brand" href="/prueba-php/public/">
                <i class="bi bi-shield-fill me-2"></i>
                Filá Mariscales
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item d-flex align-items-center">
                    <span class="user-welcome me-3">
                        <i class="bi bi-person-circle me-2"></i>
                        Bienvenido, <?php echo $data['user']->nombre; ?>
                    </span>
                    <img src="<?php echo isset($data['user']->foto) && $data['user']->foto ? '/prueba-php/public/uploads/socios/' . $data['user']->foto : 'https://via.placeholder.com/40x40/DC143C/FFFFFF?text=' . substr($data['user']->nombre, 0, 1); ?>" 
                         alt="Avatar" class="user-avatar me-2">
                    <a href="/prueba-php/public/socios/logout" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Profile and Actions Section -->
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <h3 class="card-title-custom">
                        <i class="bi bi-person-circle me-2"></i>Mi Perfil y Acciones
                    </h3>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-section">
                                <img src="<?php echo isset($data['user']->foto) && $data['user']->foto ? '/prueba-php/public/uploads/socios/' . $data['user']->foto : 'https://via.placeholder.com/150x150/DC143C/FFFFFF?text=' . substr($data['user']->nombre, 0, 1); ?>" 
                                     alt="Foto de perfil" class="profile-avatar" id="profileAvatar">
                                
                                <form id="uploadForm" enctype="multipart/form-data" style="display: none;">
                                    <input type="file" id="photoInput" name="photo" accept="image/*">
                                </form>
                                
                                <button class="btn upload-btn" onclick="document.getElementById('photoInput').click()">
                                    <i class="bi bi-camera me-2"></i>Cambiar Foto
                                </button>
                                
                                <div class="mt-3">
                                    <h5><?php echo $data['user']->nombre . ' ' . $data['user']->apellidos; ?></h5>
                                    <p class="text-muted">Nº Socio: <?php echo $data['socio_data']['numero_socio']; ?></p>
                                    <span class="badge bg-success">Socio Activo</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h5 class="text-primary mb-3">
                                        <i class="bi bi-gear me-2"></i>Acciones Disponibles
                                    </h5>
                                </div>
                                
                                <div class="col-md-6">
                                    <button class="btn action-btn w-100" onclick="printCard()">
                                        <i class="bi bi-printer"></i>Imprimir Carnet
                                    </button>
                                </div>
                                
                                <div class="col-md-6">
                                    <button class="btn action-btn w-100" onclick="showFriendsModal()">
                                        <i class="bi bi-people"></i>Gestionar Amigos
                                    </button>
                                </div>
                                
                                <div class="col-md-6">
                                    <button class="btn action-btn w-100" onclick="showEvents()">
                                        <i class="bi bi-calendar-event"></i>Ver Eventos
                                    </button>
                                </div>
                                
                                <div class="col-md-6">
                                    <button class="btn action-btn w-100" onclick="showDocuments()">
                                        <i class="bi bi-file-earmark-text"></i>Documentos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Friends Section -->
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <h3 class="card-title-custom">
                        <i class="bi bi-people-fill me-2"></i>Mis Amigos
                    </h3>
                </div>
                <div class="card-body-custom">
                    <div class="friends-grid" id="friendsGrid">
                        <!-- Friends will be loaded here -->
                    </div>
                    
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary" onclick="showAddFriendModal()">
                            <i class="bi bi-person-plus me-2"></i>Añadir Nuevo Amigo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="dashboard-card">
                <div class="card-header-custom">
                    <h3 class="card-title-custom">
                        <i class="bi bi-chat-dots me-2"></i>Comentarios y Mensajes
                    </h3>
                </div>
                <div class="card-body-custom">
                    <div class="comment-form">
                        <h5 class="mb-3">Escribir un comentario</h5>
                        <textarea class="form-control comment-input" id="commentText" placeholder="Escribe tu comentario aquí..."></textarea>
                        <button class="btn btn-primary mt-3" onclick="submitComment()">
                            <i class="bi bi-send me-2"></i>Enviar Comentario
                        </button>
                    </div>
                    
                    <div class="comments-list" id="commentsList">
                        <!-- Comments will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Friend Modal -->
    <div class="modal fade" id="addFriendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Nuevo Amigo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Buscar socio por email:</label>
                        <input type="email" class="form-control" id="friendEmail" placeholder="email@ejemplo.com">
                    </div>
                    <div id="searchResults"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="searchFriend()">Buscar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Friends Management Modal -->
    <div class="modal fade" id="friendsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gestionar Mis Amigos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="friendsManagementList"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let friends = [];
        let comments = [];

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadFriends();
            loadComments();
            setupPhotoUpload();
        });

        // Photo upload functionality
        function setupPhotoUpload() {
            const photoInput = document.getElementById('photoInput');
            const profileAvatar = document.getElementById('profileAvatar');
            
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('photo', file);
                    
                    fetch('/prueba-php/public/socios/upload-photo', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            profileAvatar.src = data.photo_url;
                            // Update navbar avatar too
                            document.querySelector('.user-avatar').src = data.photo_url;
                            showToast('Foto actualizada correctamente', 'success');
                        } else {
                            showToast('Error al subir la foto', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Error al subir la foto', 'error');
                    });
                }
            });
        }

        // Print card functionality
        function printCard() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Carnet de Socio - ${document.querySelector('.user-welcome').textContent}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                        .card { border: 2px solid #dc143c; border-radius: 10px; padding: 20px; max-width: 400px; margin: 0 auto; }
                        .header { text-align: center; border-bottom: 2px solid #dc143c; padding-bottom: 10px; margin-bottom: 20px; }
                        .photo { width: 100px; height: 100px; border-radius: 50%; border: 3px solid #dc143c; margin: 0 auto 20px; display: block; }
                        .info { margin-bottom: 10px; }
                        .label { font-weight: bold; color: #dc143c; }
                    </style>
                </head>
                <body>
                    <div class="card">
                        <div class="header">
                            <h2>Filá Mariscales</h2>
                            <h3>Carnet de Socio</h3>
                        </div>
                        <img src="${document.getElementById('profileAvatar').src}" class="photo">
                        <div class="info">
                            <span class="label">Nombre:</span> ${document.querySelector('.user-welcome').textContent.replace('Bienvenido, ', '')}
                        </div>
                        <div class="info">
                            <span class="label">Nº Socio:</span> ${document.querySelector('.text-muted').textContent.replace('Nº Socio: ', '')}
                        </div>
                        <div class="info">
                            <span class="label">Email:</span> <?php echo $data['user']->email; ?>
                        </div>
                        <div class="info">
                            <span class="label">Estado:</span> Socio Activo
                        </div>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Friends functionality
        function loadFriends() {
            // Simulate loading friends from server
            friends = [
                { id: 1, name: 'María García', email: 'maria.garcia@mariscales.com', photo: null },
                { id: 2, name: 'Antonio Rodríguez', email: 'antonio.rodriguez@mariscales.com', photo: null },
                { id: 3, name: 'Carmen López', email: 'carmen.lopez@mariscales.com', photo: null }
            ];
            renderFriends();
        }

        function renderFriends() {
            const friendsGrid = document.getElementById('friendsGrid');
            friendsGrid.innerHTML = friends.map(friend => `
                <div class="friend-card">
                    <img src="${friend.photo || `https://via.placeholder.com/60x60/DC143C/FFFFFF?text=${friend.name.charAt(0)}`}" 
                         alt="${friend.name}" class="friend-avatar">
                    <div class="friend-name">${friend.name}</div>
                    <div class="friend-email">${friend.email}</div>
                    <button class="btn btn-sm btn-outline-danger mt-2" onclick="removeFriend(${friend.id})">
                        <i class="bi bi-person-x"></i> Eliminar
                    </button>
                </div>
            `).join('');
        }

        function showAddFriendModal() {
            const modal = new bootstrap.Modal(document.getElementById('addFriendModal'));
            modal.show();
        }

        function searchFriend() {
            const email = document.getElementById('friendEmail').value;
            const resultsDiv = document.getElementById('searchResults');
            
            // Simulate search
            if (email) {
                resultsDiv.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Juan Carlos Martínez</strong> (${email})
                        <button class="btn btn-sm btn-primary float-end" onclick="addFriend(4, 'Juan Carlos Martínez', '${email}')">
                            <i class="bi bi-person-plus"></i> Añadir
                        </button>
                    </div>
                `;
            } else {
                resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor ingresa un email</div>';
            }
        }

        function addFriend(id, name, email) {
            friends.push({ id, name, email, photo: null });
            renderFriends();
            bootstrap.Modal.getInstance(document.getElementById('addFriendModal')).hide();
            showToast('Amigo añadido correctamente', 'success');
        }

        function removeFriend(id) {
            friends = friends.filter(friend => friend.id !== id);
            renderFriends();
            showToast('Amigo eliminado', 'info');
        }

        function showFriendsModal() {
            const modal = new bootstrap.Modal(document.getElementById('friendsModal'));
            const listDiv = document.getElementById('friendsManagementList');
            
            listDiv.innerHTML = friends.map(friend => `
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <strong>${friend.name}</strong><br>
                        <small class="text-muted">${friend.email}</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFriend(${friend.id})">
                        <i class="bi bi-person-x"></i> Eliminar
                    </button>
                </div>
            `).join('');
            
            modal.show();
        }

        // Comments functionality
        function loadComments() {
            // Simulate loading comments from server
            comments = [
                { id: 1, author: 'María García', text: '¡Hola a todos! ¿Alguien va al próximo ensayo?', date: '2024-01-15 10:30' },
                { id: 2, author: 'Antonio Rodríguez', text: 'Yo sí voy, nos vemos allí', date: '2024-01-15 11:15' },
                { id: 3, author: 'Carmen López', text: 'Perfecto, será un buen ensayo', date: '2024-01-15 12:00' }
            ];
            renderComments();
        }

        function renderComments() {
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = comments.map(comment => `
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author">${comment.author}</span>
                        <span class="comment-date">${comment.date}</span>
                    </div>
                    <div class="comment-text">${comment.text}</div>
                </div>
            `).join('');
        }

        function submitComment() {
            const commentText = document.getElementById('commentText').value.trim();
            if (commentText) {
                const newComment = {
                    id: comments.length + 1,
                    author: '<?php echo $data['user']->nombre . ' ' . $data['user']->apellidos; ?>',
                    text: commentText,
                    date: new Date().toLocaleString('es-ES')
                };
                
                comments.unshift(newComment);
                renderComments();
                document.getElementById('commentText').value = '';
                showToast('Comentario enviado correctamente', 'success');
            } else {
                showToast('Por favor escribe un comentario', 'warning');
            }
        }

        // Utility functions
        function showToast(message, type) {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = message;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showEvents() {
            showToast('Funcionalidad de eventos en desarrollo', 'info');
        }

        function showDocuments() {
            showToast('Funcionalidad de documentos en desarrollo', 'info');
        }
    </script>
</body>
</html>
