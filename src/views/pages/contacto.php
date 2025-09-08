<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 text-white">
                        <i class="bi bi-envelope me-2"></i>
                        Contacto
                    </h2>
                </div>
                <div class="card-body">
                    <p class="lead text-center mb-4">
                        Ponte en contacto con la <strong>Filá Mariscales de Caballeros Templarios de Elche</strong>
                    </p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-primary mb-3">
                                <i class="bi bi-geo-alt me-2"></i>
                                Información de Contacto
                            </h4>
                            
                            <div class="contact-info">
                                <div class="mb-3">
                                    <strong><i class="bi bi-house me-2"></i>Dirección:</strong><br>
                                    <span class="ms-4">Sede Social de la Filá Mariscales<br>
                                    Elche, Alicante, España</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong><br>
                                    <span class="ms-4">+34 XXX XXX XXX</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="bi bi-envelope me-2"></i>Email:</strong><br>
                                    <span class="ms-4">info@filamariscales.com</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong><i class="bi bi-clock me-2"></i>Horario de Atención:</strong><br>
                                    <span class="ms-4">Lunes a Viernes: 18:00 - 20:00<br>
                                    Sábados: 10:00 - 12:00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h4 class="text-primary mb-3">
                                <i class="bi bi-send me-2"></i>
                                Envíanos un Mensaje
                            </h4>
                            
                            <form id="contactForm">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="asunto" class="form-label">Asunto *</label>
                                    <select class="form-select" id="asunto" name="asunto" required>
                                        <option value="">Selecciona un asunto</option>
                                        <option value="informacion">Información General</option>
                                        <option value="socios">Información para Socios</option>
                                        <option value="eventos">Eventos y Actividades</option>
                                        <option value="colaboracion">Colaboración</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="mensaje" class="form-label">Mensaje *</label>
                                    <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required 
                                              placeholder="Escribe tu mensaje aquí..."></textarea>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="privacidad" name="privacidad" required>
                                    <label class="form-check-label" for="privacidad">
                                        Acepto la <a href="#" class="text-primary">política de privacidad</a> *
                                    </label>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send me-2"></i>
                                        Enviar Mensaje
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <hr class="my-5">
                    
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="contact-card p-3">
                                <i class="bi bi-people fa-3x text-primary mb-3"></i>
                                <h5>Únete a Nosotros</h5>
                                <p class="text-muted">¿Interesado en formar parte de nuestra filá? Contacta con nosotros para más información.</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="contact-card p-3">
                                <i class="bi bi-calendar-event fa-3x text-primary mb-3"></i>
                                <h5>Eventos</h5>
                                <p class="text-muted">Consulta sobre nuestros próximos eventos y actividades programadas.</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="contact-card p-3">
                                <i class="bi bi-handshake fa-3x text-primary mb-3"></i>
                                <h5>Colaboraciones</h5>
                                <p class="text-muted">¿Tienes una propuesta de colaboración? Estamos abiertos a nuevas ideas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-info {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.5rem;
    border-left: 4px solid #007bff;
}

.contact-card {
    transition: transform 0.3s ease;
    border-radius: 0.5rem;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

#contactForm .form-control:focus,
#contactForm .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}
</style>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Aquí puedes agregar la lógica para enviar el formulario
    // Por ahora, solo mostramos un mensaje de confirmación
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Simular envío del formulario
    alert('¡Mensaje enviado correctamente! Te responderemos en breve.');
    
    // Limpiar el formulario
    this.reset();
});
</script>
