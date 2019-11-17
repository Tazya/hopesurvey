<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* home.twig */
class __TwigTemplate_f1ba36cfebdee21feb75a1d128b0c08c6b492354c648a228a1f8f09f6915ee00 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'contents' => [$this, 'block_contents'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("html.twig", "home.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_contents($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "    <h1>
        Slim 4 Skeleton
    </h1>

    <p>
        This is a skeleton for a web application that uses the Slim 4 Framework.
    </p>

    <h2 class=\"mt-4\">
        PSR-7 implementation
    </h2>

    <p>
        By default this project uses the Nyholm PSR-7 implementation. But you are
        free to use any other PSR-7 implementation. Just modify the <code>composer.json</code>
        file. The two packages are <code>nyholm/psr7</code> and <code>nyholm/psr7-server</code>.
    </p>

    <h2 class=\"mt-4\">
        Dependency injection
    </h2>

    <p>
        By default this project uses the PHP-DI package to build a <code>Container</code>
        for dependency injection.
    </p>

    <h2 class=\"mt-4\">
        Slim Twig extension
    </h2>

    <div class=\"table-responsive\">
        <table class=\"table table-sm\">
            <thead>
            <tr>
                <th scope=\"col\">Code</th>
                <th scope=\"col\">Result</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <code>url_for('hello', {'name': 'foo'})</code>
                </td>
                <td>
                    ";
        // line 49
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->urlFor("hello", ["name" => "foo"]), "html", null, true);
        echo "
                </td>
            </tr>
            <tr>
                <td>
                    <code>full_url_for('home')</code>
                </td>
                <td>
                    ";
        // line 57
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
        echo "
                </td>
            </tr>
            <tr>
                <td>
                    <code>is_current_url('home')</code>
                </td>
                <td>
                    ";
        // line 65
        echo (($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("home")) ? ("TRUE") : ("FALSE"));
        echo "
                </td>
            </tr>
            <tr>
                <td>
                    <code>current_url()</code>
                </td>
                <td>
                    ";
        // line 73
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->getCurrentUrl(), "html", null, true);
        echo "
                </td>
            </tr>
            <tr>
                <td>
                    <code>get_uri().scheme</code>
                </td>
                <td>
                    ";
        // line 81
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $this->extensions['Slim\Views\TwigExtension']->getUri(), "scheme", [], "any", false, false, false, 81), "html", null, true);
        echo "
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <h2 class=\"mt-4\">
        Filesystem
    </h2>

    <p>
        The root path for this project is <code>";
        // line 93
        echo twig_escape_filter($this->env, ($context["rootPath"] ?? null), "html", null, true);
        echo "</code>.
    </p>

";
    }

    public function getTemplateName()
    {
        return "home.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  156 => 93,  141 => 81,  130 => 73,  119 => 65,  108 => 57,  97 => 49,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "home.twig", "C:\\_learn\\hope\\survey\\application\\templates\\home.twig");
    }
}
