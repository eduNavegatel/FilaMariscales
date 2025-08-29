/**
 * 📱 Script para generar APK de Filá Mariscales
 * Utiliza PWA Builder para convertir la PWA en APK
 */

const https = require('https');
const fs = require('fs');
const path = require('path');

class APKGenerator {
    constructor() {
        this.pwaBuilderUrl = 'https://www.pwabuilder.com/api';
        this.manifestUrl = 'https://tu-dominio.com/manifest.json'; // Cambiar por tu dominio real
        this.outputDir = './apk-output';
    }
    
    async generateAPK() {
        console.log('🚀 Iniciando generación de APK...');
        
        try {
            // 1. Crear directorio de salida
            this.createOutputDirectory();
            
            // 2. Validar PWA
            console.log('📋 Validando PWA...');
            const validation = await this.validatePWA();
            
            if (!validation.isValid) {
                console.error('❌ PWA no válida:', validation.errors);
                return false;
            }
            
            console.log('✅ PWA válida');
            
            // 3. Generar APK
            console.log('📱 Generando APK...');
            const apkResult = await this.buildAPK();
            
            if (apkResult.success) {
                console.log('✅ APK generado correctamente');
                console.log('📁 Ubicación:', apkResult.filePath);
                return true;
            } else {
                console.error('❌ Error generando APK:', apkResult.error);
                return false;
            }
            
        } catch (error) {
            console.error('❌ Error en el proceso:', error);
            return false;
        }
    }
    
    createOutputDirectory() {
        if (!fs.existsSync(this.outputDir)) {
            fs.mkdirSync(this.outputDir, { recursive: true });
            console.log('📁 Directorio de salida creado:', this.outputDir);
        }
    }
    
    async validatePWA() {
        return new Promise((resolve) => {
            const url = `${this.pwaBuilderUrl}/validate?url=${encodeURIComponent(this.manifestUrl)}`;
            
            https.get(url, (res) => {
                let data = '';
                
                res.on('data', (chunk) => {
                    data += chunk;
                });
                
                res.on('end', () => {
                    try {
                        const result = JSON.parse(data);
                        resolve({
                            isValid: result.isValid || false,
                            errors: result.errors || []
                        });
                    } catch (error) {
                        resolve({
                            isValid: false,
                            errors: ['Error parsing validation response']
                        });
                    }
                });
            }).on('error', (error) => {
                resolve({
                    isValid: false,
                    errors: [error.message]
                });
            });
        });
    }
    
    async buildAPK() {
        return new Promise((resolve) => {
            const buildData = JSON.stringify({
                url: this.manifestUrl,
                platform: 'android',
                options: {
                    packageId: 'com.filamariscales.app',
                    packageName: 'Filá Mariscales',
                    versionCode: '1',
                    versionName: '1.0.0',
                    enableNotifications: true,
                    enableSplashScreen: true,
                    splashScreenColor: '#dc143c',
                    adaptiveIcon: {
                        foregroundImage: '/assets/images/icons/icon-512x512.png',
                        backgroundColor: '#dc143c'
                    }
                }
            });
            
            const options = {
                hostname: 'www.pwabuilder.com',
                port: 443,
                path: '/api/build',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Content-Length': Buffer.byteLength(buildData)
                }
            };
            
            const req = https.request(options, (res) => {
                let data = '';
                
                res.on('data', (chunk) => {
                    data += chunk;
                });
                
                res.on('end', () => {
                    try {
                        const result = JSON.parse(data);
                        
                        if (result.success) {
                            // Descargar el APK
                            this.downloadAPK(result.downloadUrl, (success, filePath) => {
                                resolve({
                                    success: success,
                                    filePath: filePath,
                                    error: success ? null : 'Error downloading APK'
                                });
                            });
                        } else {
                            resolve({
                                success: false,
                                error: result.error || 'Unknown build error'
                            });
                        }
                    } catch (error) {
                        resolve({
                            success: false,
                            error: 'Error parsing build response'
                        });
                    }
                });
            });
            
            req.on('error', (error) => {
                resolve({
                    success: false,
                    error: error.message
                });
            });
            
            req.write(buildData);
            req.end();
        });
    }
    
    downloadAPK(downloadUrl, callback) {
        const fileName = `fila-mariscales-${Date.now()}.apk`;
        const filePath = path.join(this.outputDir, fileName);
        
        const file = fs.createWriteStream(filePath);
        
        https.get(downloadUrl, (res) => {
            res.pipe(file);
            
            file.on('finish', () => {
                file.close();
                console.log('📥 APK descargado:', filePath);
                callback(true, filePath);
            });
        }).on('error', (error) => {
            fs.unlink(filePath, () => {}); // Eliminar archivo parcial
            console.error('❌ Error descargando APK:', error);
            callback(false, null);
        });
    }
    
    // Método alternativo usando Bubblewrap (Google)
    async generateWithBubblewrap() {
        console.log('🔄 Usando método alternativo con Bubblewrap...');
        
        const bubblewrapConfig = {
            packageId: 'com.filamariscales.app',
            packageName: 'Filá Mariscales',
            versionCode: '1',
            versionName: '1.0.0',
            manifestUrl: this.manifestUrl,
            webManifestUrl: this.manifestUrl,
            backgroundColor: '#dc143c',
            themeColor: '#dc143c',
            display: 'standalone',
            orientation: 'portrait',
            iconUrl: '/assets/images/icons/icon-512x512.png',
            adaptiveIconUrl: '/assets/images/icons/icon-512x512.png',
            maskableIconUrl: '/assets/images/icons/icon-512x512.png',
            monochromeIconUrl: '/assets/images/icons/icon-512x512.png',
            shortcuts: [
                {
                    name: 'Historia',
                    shortName: 'Historia',
                    url: '/libro',
                    iconUrl: '/assets/images/icons/history-icon.png'
                },
                {
                    name: 'Galería',
                    shortName: 'Galería',
                    url: '/galeria',
                    iconUrl: '/assets/images/icons/gallery-icon.png'
                },
                {
                    name: 'Eventos',
                    shortName: 'Eventos',
                    url: '/eventos',
                    iconUrl: '/assets/images/icons/events-icon.png'
                }
            ]
        };
        
        // Guardar configuración
        const configPath = path.join(this.outputDir, 'bubblewrap-config.json');
        fs.writeFileSync(configPath, JSON.stringify(bubblewrapConfig, null, 2));
        
        console.log('📋 Configuración de Bubblewrap guardada:', configPath);
        console.log('💡 Para generar el APK con Bubblewrap:');
        console.log('   1. Instalar Bubblewrap: npm install -g @bubblewrap/cli');
        console.log('   2. Ejecutar: bubblewrap build --config', configPath);
        
        return true;
    }
}

// ===== FUNCIÓN PRINCIPAL =====
async function main() {
    console.log('📱 Generador de APK para Filá Mariscales');
    console.log('==========================================');
    
    const generator = new APKGenerator();
    
    // Verificar argumentos
    const args = process.argv.slice(2);
    const useBubblewrap = args.includes('--bubblewrap') || args.includes('-b');
    
    if (useBubblewrap) {
        await generator.generateWithBubblewrap();
    } else {
        const success = await generator.generateAPK();
        
        if (!success) {
            console.log('\n🔄 Intentando método alternativo...');
            await generator.generateWithBubblewrap();
        }
    }
    
    console.log('\n✅ Proceso completado');
    console.log('📁 Revisa el directorio:', generator.outputDir);
}

// Ejecutar si es el archivo principal
if (require.main === module) {
    main().catch(console.error);
}

module.exports = APKGenerator;

