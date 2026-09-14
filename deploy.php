<?php
namespace Deployer;

require 'recipe/common.php';

// ---------------------------------------------------------------------------
// Deployer config para cruzdeossos.com.br (Hostinger VPS)
// Uso:  php deployer.phar deploy production
//       php deployer.phar deploy production -v   (verbose)
// ---------------------------------------------------------------------------

// Nome do projeto
set('application', 'cruzdeossos');

// Repositório (clonado na VPS via deploy key do usuário deploy)
set('repository', 'git@github-cruzdeossos:diogocolete/cruzdeossos.git');

// Branch a ser deployada
set('branch', 'main');

// Arquivos/dirs compartilhados entre releases (não sobrescritos)
add('shared_files', ['.env']);
add('shared_dirs', ['storage']);

// Arquivos/dirs com permissões especiais (writable)
add('writable_dirs', ['storage', 'bootstrap/cache']);

// Usuário SSH e diretório base na VPS
set('remote_user', 'deploy');
set('deploy_path', '/var/www/html/cruzdeossos');

// PHP e composer na VPS
set('bin/php', 'php');
set('bin/composer', 'composer');

// ---------------------------------------------------------------------------
// Hosts
// ---------------------------------------------------------------------------
host('2.25.96.11')
    ->user('deploy')
    ->identityFile('~/.ssh/colete_hostinger')
    ->set('deploy_path', '/var/www/html/cruzdeossos')
    ->stage('production');

// ---------------------------------------------------------------------------
// Tarefas customizadas
// ---------------------------------------------------------------------------

// Limpa caches antigos e gera novos (Laravel 11 — config:cache funciona)
task('artisan:cache', function () {
    $release = get('release_path');
    within($release, function () {
        run('{{bin/php}} artisan cache:clear');
        run('{{bin/php}} artisan config:clear');
        run('{{bin/php}} artisan route:clear');
        run('{{bin/php}} artisan view:clear');
        run('{{bin/php}} artisan migrate --force');
        run('{{bin/php}} artisan config:cache');
        run('{{bin/php}} artisan route:cache');
        run('{{bin/php}} artisan view:cache');
    });
})->desc('Limpa caches, roda migrations e regera caches do Laravel');

// Cria o symlink public/storage -> storage/app/public
task('artisan:storage:link', function () {
    $release = get('release_path');
    within($release, function () {
        run('{{bin/php}} artisan storage:link');
    });
})->desc('Cria symlink de storage público');

// Corrige permissões de storage e bootstrap/cache
task('permissions:set', function () {
    $release = get('release_path');
    $deployPath = get('deploy_path');
    // storage é symlink para shared — ajusta no shared
    run("sudo chown -R deploy:www-data {$deployPath}/shared/storage {$release}/bootstrap/cache");
    run("sudo chmod -R 775 {$deployPath}/shared/storage {$release}/bootstrap/cache");
})->desc('Ajusta permissões');

// Recarrega o Apache
task('apache:reload', function () {
    run('sudo systemctl reload apache2');
})->desc('Recarrega Apache');

// ---------------------------------------------------------------------------
// Pipeline de deploy
// ---------------------------------------------------------------------------
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'artisan:cache',
    'artisan:storage:link',
    'permissions:set',
    'deploy:symlink',
    'deploy:unlock',
    'apache:reload',
]);

// Hook após falha: libera lock
after('deploy:failed', 'deploy:unlock');
