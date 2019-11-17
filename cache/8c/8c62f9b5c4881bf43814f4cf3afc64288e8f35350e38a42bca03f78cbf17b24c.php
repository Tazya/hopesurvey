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

/* html.twig */
class __TwigTemplate_0999f9d973218e932ad42accd661927bb6c81f7bfda40b8be9c2ebde6c5df64c extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'contents' => [$this, 'block_contents'],
            'scripts' => [$this, 'block_scripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!doctype html>
<html lang=\"en\">
<head>
    <!-- Required meta tags -->
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">

    <!-- Bootstrap CSS -->
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css\"
          integrity=\"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T\" crossorigin=\"anonymous\">

    <title>";
        // line 12
        echo twig_escape_filter($this->env, ($context["pageTitle"] ?? null), "html", null, true);
        echo "</title>
</head>

<body>

<nav class=\"navbar navbar-expand-lg navbar-light bg-light\">
    <div class=\"container\">
        <a class=\"navbar-brand\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->urlFor("home"), "html", null, true);
        echo "\">Slim 4 Skeleton</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\"
                aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">
            <ul class=\"navbar-nav mr-auto\">
                <li class=\"nav-item";
        // line 27
        if ($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("home")) {
            echo " active";
        }
        echo "\">
                    <a class=\"nav-link\" href=\"";
        // line 28
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->urlFor("home"), "html", null, true);
        echo "\">
                        Home
                        ";
        // line 30
        if ($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("home")) {
            // line 31
            echo "                            <span class=\"sr-only\">(current)</span>
                        ";
        }
        // line 33
        echo "                    </a>
                </li>
                <li class=\"nav-item";
        // line 35
        if ($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("hello", ["name" => "Slim"])) {
            echo " active";
        }
        echo "\">
                    <a class=\"nav-link\" href=\"";
        // line 36
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->urlFor("hello", ["name" => "Slim"]), "html", null, true);
        echo "\">
                        Hello Slim
                        ";
        // line 38
        if ($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("hello", ["name" => "Slim"])) {
            // line 39
            echo "                            <span class=\"sr-only\">(current)</span>
                        ";
        }
        // line 41
        echo "                    </a>
                </li>
                <li class=\"nav-item";
        // line 43
        if ($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("exception-demo")) {
            echo " active";
        }
        echo "\">
                    <a class=\"nav-link\" href=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->extensions['Slim\Views\TwigExtension']->urlFor("exception-demo"), "html", null, true);
        echo "\">
                        Exception Demo
                        ";
        // line 46
        if ($this->extensions['Slim\Views\TwigExtension']->isCurrentUrl("exception-demo")) {
            // line 47
            echo "                            <span class=\"sr-only\">(current)</span>
                        ";
        }
        // line 49
        echo "                    </a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link disabled\" href=\"#\" tabindex=\"-1\" aria-disabled=\"true\">Disabled</a>
                </li>
            </ul>
            <form class=\"form-inline my-2 my-lg-0\">
                <input class=\"form-control mr-sm-2\" type=\"search\" placeholder=\"Search\" aria-label=\"Search\">
                <button class=\"btn btn-outline-success my-2 my-sm-0\" type=\"submit\">Search</button>
            </form>
        </div>
    </div>
</nav>

<div class=\"container mt-4\">
    ";
        // line 64
        $this->displayBlock('contents', $context, $blocks);
        // line 66
        echo "</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\"
        integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\"
        crossorigin=\"anonymous\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js\"
        integrity=\"sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1\"
        crossorigin=\"anonymous\"></script>
<script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js\"
        integrity=\"sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM\"
        crossorigin=\"anonymous\"></script>
";
        // line 79
        $this->displayBlock('scripts', $context, $blocks);
        // line 80
        echo "</body>
</html>
";
    }

    // line 64
    public function block_contents($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 65
        echo "    ";
    }

    // line 79
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
    }

    public function getTemplateName()
    {
        return "html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  182 => 79,  178 => 65,  174 => 64,  168 => 80,  166 => 79,  151 => 66,  149 => 64,  132 => 49,  128 => 47,  126 => 46,  121 => 44,  115 => 43,  111 => 41,  107 => 39,  105 => 38,  100 => 36,  94 => 35,  90 => 33,  86 => 31,  84 => 30,  79 => 28,  73 => 27,  62 => 19,  52 => 12,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "html.twig", "C:\\_learn\\hope\\survey\\application\\templates\\html.twig");
    }
}
