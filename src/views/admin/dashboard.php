<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Panel de Control</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="/prueba-php/public/admin/export/dashboard" class="btn btn-sm btn-outline-secondary" title="Exportar CSV">
                Exportar
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()" title="Imprimir página">
                Imprimir
            </button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Usuarios Registrados</h5>
                        <h2 class="mb-0"><?= $data['userCount'] ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/usuarios" class="text-white mt-2 d-block">Ver usuarios <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Eventos Activos</h5>
                        <h2 class="mb-0"><?= $data['eventCount'] ?></h2>
                    </div>
                    <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/eventos" class="text-white mt-2 d-block">Ver eventos <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Galería</h5>
                        <h2 class="mb-0"><?= isset($data['galleryCount']) ? $data['galleryCount'] : 0 ?></h2>
                    </div>
                    <i class="fas fa-images fa-3x opacity-50"></i>
                </div>
                <a href="<?= URL_ROOT ?>/admin/galeria" class="text-white mt-2 d-block">Gestionar galería <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Acciones Rápidas</h5>
                        <h2 class="mb-0">+</h2>
                    </div>
                    <i class="fas fa-plus fa-3x opacity-50"></i>
                </div>
                <div class="mt-2">
                                            <a href="/prueba-php/public/admin/crearUsuario" class="text-white d-block small">Nuevo usuario</a>
                                            <a href="/prueba-php/public/admin/galeria" class="text-white d-block small">Subir archivos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Últimos Usuarios Registrados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['recentUsers'])): ?>
                                <?php foreach ($data['recentUsers'] as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user->nombre . ' ' . $user->apellidos) ?></td>
                                        <td><?= htmlspecialchars($user->email) ?></td>
                                        <td><span class="badge bg-<?= $user->rol === 'admin' ? 'primary' : 'secondary' ?>">
                                            <?= ucfirst($user->rol) ?>
                                        </span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-3">No hay usuarios registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Próximos Eventos</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($data['recentEvents'])): ?>
                        <?php foreach ($data['recentEvents'] as $event): ?>
                            <a href="<?= URL_ROOT ?>/admin/events/<?= $event->id ?>/edit" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($event->titulo) ?></h6>
                                    <small class="text-muted">
                                        <?= date('d M', strtotime($event->fecha)) ?>
                                    </small>
                                </div>
                                <p class="mb-1 text-muted">
                                    <i class="far fa-clock me-1"></i> 
                                    <?= date('H:i', strtotime($event->hora)) ?> - 
                                    <?= htmlspecialchars($event->lugar) ?>
                                </p>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3">No hay eventos próximos</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Actividad Reciente</h6>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-primary d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Nuevo usuario registrado</h6>
                            <p class="text-muted small mb-0">Juan Pérez se ha registrado en el sistema</p>
                            <small class="text-muted">Hace 2 horas</small>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-light rounded-circle text-warning d-flex align-items-center justify-content-center">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Intento de inicio de sesión fallido</h6>
                            <p class="text-muted small mb-0">Se detectó un intento de inicio de sesión fallido para admin@example.com</p>
                            <small class="text-muted">Ayer a las 08:15</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
