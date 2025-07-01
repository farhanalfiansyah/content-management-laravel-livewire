<?php
// Simple cache clearing script for shared hosting
// Access this via: yourdomain.com/clear-cache.php
// DELETE THIS FILE AFTER USE FOR SECURITY!

// Change directory to Laravel root
chdir(__DIR__ . '/..');

$commands = [
    'php artisan config:clear',
    'php artisan view:clear', 
    'php artisan cache:clear',
    'php artisan route:clear'
];

echo "<h2>🚀 Laravel Cache Clearing Script</h2>";
echo "<p><strong>⚠️ DELETE THIS FILE AFTER USE!</strong></p>";
echo "<hr>";

foreach ($commands as $command) {
    echo "<p>Running: <code>$command</code></p>";
    $output = shell_exec($command . ' 2>&1');
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>$output</pre>";
    echo "<hr>";
}

echo "<h3>✅ Cache clearing completed!</h3>";
echo "<p style='color: red;'><strong>🛡️ IMPORTANT: Delete this file immediately for security!</strong></p>";
?>

<script>
// Auto-delete this script after 30 seconds (optional)
setTimeout(function() {
    if(confirm('Auto-delete this script file for security?')) {
        fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
            method: 'DELETE'
        }).then(() => {
            alert('Script deleted successfully!');
            window.close();
        });
    }
}, 30000);
</script> 