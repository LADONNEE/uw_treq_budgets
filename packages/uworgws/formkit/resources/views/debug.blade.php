<div>
    <table class="table">
        <thead>
        <tr>
            <th>Variable</th>
            <th>Value</th>
            <th>Description</th>
        </tr>
        </thead>

        <tbody>

        <tr>
            <td>$name</td>
            <td>{{ $name }}</td>
            <td>HTML name="" attribute</td>
        </tr>

        <tr>
            <td>$type</td>
            <td>{{ $type }}</td>
            <td>Type of input</td>
        </tr>

        <tr>
            <td>$view</td>
            <td>{{ $view }}</td>
            <td>View template file used to render the input HTML</td>
        </tr>

        <tr>
            <td>$value</td>
            <td>{{ (is_array($value)) ? implode(', ', $value) : $value }}</td>
            <td>Current value</td>
        </tr>

        <tr>
            <td>$error</td>
            <td>{{ $error }}</td>
            <td>Error message</td>
        </tr>

        <tr>
            <td>$label</td>
            <td>{{ $label }}</td>
            <td>Text to use as HTML &lt;label&gt;</td>
        </tr>

        <tr>
            <td>$id</td>
            <td>{{ $id }}</td>
            <td>HTML id="" attribute</td>
        </tr>

        <tr>
            <td>$class</td>
            <td>{{ $class }}</td>
            <td>CSS class name(s)</td>
        </tr>

        <tr>
            <td>$required</td>
            <td>{{ ($required) ? 'True' : 'False' }}</td>
            <td>True if this is a required field</td>
        </tr>

        <tr>
            <td>$help</td>
            <td>{{ $help }}</td>
            <td>Text to display as user help</td>
        </tr>

        <tr>
            <td>$helpId</td>
            <td>{{ $helpId }}</td>
            <td>HTML id to be assigned the help element</td>
        </tr>

        <tr>
            <td>$htmlAttributes</td>
            <td>{{ $htmlAttributes }}</td>
            <td>String additional HTML attributes to add to input</td>
        </tr>

        <tr>
            <td>$booleanText</td>
            <td>{{ $booleanText }}</td>
            <td>Text to be displayed on a boolean (true/false) input</td>
        </tr>

        </tbody>
    </table>
</div>
