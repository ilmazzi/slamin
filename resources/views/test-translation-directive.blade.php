<!DOCTYPE html>
<html>
<head>
    <title>Test {{ __() Directive</title>
</head>
<body>
    <h1>Test Nuova Direttiva {{ __()</h1>
    
    <h2>Metodo 1: Chiave esistente</h2>
    <p>{{ __('common.welcome')</p>
    
    <h2>Metodo 2: Testo + File (auto-create)</h2>
    <p>{{ __('Benvenuto su Slam In', 'home')</p>
    
    <h2>Metodo 3: Solo testo (auto-create in 'auto' file)</h2>
    <p>{{ __('Questo è un test automatico')</p>
    
    <h2>Metodo 4: Con parametri</h2>
    <p>{{ __('common.hello', ['name' => 'Davide']) }}</p>
    
    <h2>Metodo 5: Con 3 parametri (testo, file, sezione)</h2>
    <p>{{ __('Nuovi articoli', 'home', 'articles_section')</p>
    <p>{{ __('Popolari', 'home', 'articles_section')</p>
</body>
</html>

