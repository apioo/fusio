<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 * 
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Template;

use Fusio\Template\Filter\Json;
use Fusio\Template\Filter\Prepare;
use Twig_Extension_Core;

/**
 * Extension
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Extension extends Twig_Extension_Core
{
    public function getTokenParsers()
    {
        return array(
            new \Twig_TokenParser_For(),
            new \Twig_TokenParser_If(),
            new \Twig_TokenParser_Filter(),
            new \Twig_TokenParser_Macro(),
            new \Twig_TokenParser_Set(),
            new \Twig_TokenParser_Flush(),
            new \Twig_TokenParser_Do(),
        );
    }

    public function getFilters()
    {
        $filters = array(
            // formatting filters
            new \Twig_SimpleFilter('date', 'twig_date_format_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('date_modify', 'twig_date_modify_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('format', 'sprintf'),
            new \Twig_SimpleFilter('replace', 'strtr'),
            new \Twig_SimpleFilter('number_format', 'twig_number_format_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('abs', 'abs'),
            new \Twig_SimpleFilter('round', 'twig_round'),
            // encoding
            new \Twig_SimpleFilter('url_encode', 'twig_urlencode_filter'),
            new \Twig_SimpleFilter('json_encode', 'twig_jsonencode_filter'),
            new \Twig_SimpleFilter('convert_encoding', 'twig_convert_encoding'),
            // string filters
            new \Twig_SimpleFilter('title', 'twig_title_string_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('capitalize', 'twig_capitalize_string_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('upper', 'strtoupper'),
            new \Twig_SimpleFilter('lower', 'strtolower'),
            new \Twig_SimpleFilter('striptags', 'strip_tags'),
            new \Twig_SimpleFilter('trim', 'trim'),
            // array helpers
            new \Twig_SimpleFilter('join', 'twig_join_filter'),
            new \Twig_SimpleFilter('split', 'twig_split_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('sort', 'twig_sort_filter'),
            new \Twig_SimpleFilter('merge', 'twig_array_merge'),
            new \Twig_SimpleFilter('batch', 'twig_array_batch'),
            // string/array filters
            new \Twig_SimpleFilter('reverse', 'twig_reverse_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('length', 'twig_length_filter', array('needs_environment' => true)),
            new \Twig_SimpleFilter('slice', 'twig_slice', array('needs_environment' => true)),
            new \Twig_SimpleFilter('first', 'twig_first', array('needs_environment' => true)),
            new \Twig_SimpleFilter('last', 'twig_last', array('needs_environment' => true)),
            // iteration and runtime
            new \Twig_SimpleFilter('default', '_twig_default_filter', array('node_class' => 'Twig_Node_Expression_Filter_Default')),
            new \Twig_SimpleFilter('keys', 'twig_get_array_keys_filter'),
            // fusio
            new \Twig_SimpleFilter(Prepare::FILTER_NAME, new Prepare()),
            new \Twig_SimpleFilter(Json::FILTER_NAME, new Json()),
        );
        if (function_exists('mb_get_info')) {
            $filters[] = new \Twig_SimpleFilter('upper', 'twig_upper_filter', array('needs_environment' => true));
            $filters[] = new \Twig_SimpleFilter('lower', 'twig_lower_filter', array('needs_environment' => true));
        }
        return $filters;
    }

    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('even', null, array('node_class' => 'Twig_Node_Expression_Test_Even')),
            new \Twig_SimpleTest('odd', null, array('node_class' => 'Twig_Node_Expression_Test_Odd')),
            new \Twig_SimpleTest('defined', null, array('node_class' => 'Twig_Node_Expression_Test_Defined')),
            new \Twig_SimpleTest('sameas', null, array('node_class' => 'Twig_Node_Expression_Test_Sameas')),
            new \Twig_SimpleTest('same as', null, array('node_class' => 'Twig_Node_Expression_Test_Sameas')),
            new \Twig_SimpleTest('none', null, array('node_class' => 'Twig_Node_Expression_Test_Null')),
            new \Twig_SimpleTest('null', null, array('node_class' => 'Twig_Node_Expression_Test_Null')),
            new \Twig_SimpleTest('divisibleby', null, array('node_class' => 'Twig_Node_Expression_Test_Divisibleby')),
            new \Twig_SimpleTest('divisible by', null, array('node_class' => 'Twig_Node_Expression_Test_Divisibleby')),
            new \Twig_SimpleTest('constant', null, array('node_class' => 'Twig_Node_Expression_Test_Constant')),
            new \Twig_SimpleTest('empty', 'twig_test_empty'),
            new \Twig_SimpleTest('iterable', 'twig_test_iterable'),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('max', 'max'),
            new \Twig_SimpleFunction('min', 'min'),
            new \Twig_SimpleFunction('range', 'range'),
            new \Twig_SimpleFunction('cycle', 'twig_cycle'),
            new \Twig_SimpleFunction('random', 'twig_random', array('needs_environment' => true)),
            new \Twig_SimpleFunction('date', 'twig_date_converter', array('needs_environment' => true)),
        );
    }

    public function getOperators()
    {
        return array(
            array(
                'not' => array('precedence' => 50, 'class' => 'Twig_Node_Expression_Unary_Not'),
                '-' => array('precedence' => 500, 'class' => 'Twig_Node_Expression_Unary_Neg'),
                '+' => array('precedence' => 500, 'class' => 'Twig_Node_Expression_Unary_Pos'),
            ),
            array(
                'or' => array('precedence' => 10, 'class' => 'Twig_Node_Expression_Binary_Or', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'and' => array('precedence' => 15, 'class' => 'Twig_Node_Expression_Binary_And', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'b-or' => array('precedence' => 16, 'class' => 'Twig_Node_Expression_Binary_BitwiseOr', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'b-xor' => array('precedence' => 17, 'class' => 'Twig_Node_Expression_Binary_BitwiseXor', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'b-and' => array('precedence' => 18, 'class' => 'Twig_Node_Expression_Binary_BitwiseAnd', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '==' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Equal', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '!=' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_NotEqual', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '<' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Less', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '>' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Greater', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '>=' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_GreaterEqual', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '<=' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_LessEqual', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'not in' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_NotIn', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'in' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_In', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'matches' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_Matches', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'starts with' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_StartsWith', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'ends with' => array('precedence' => 20, 'class' => 'Twig_Node_Expression_Binary_EndsWith', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '..' => array('precedence' => 25, 'class' => 'Twig_Node_Expression_Binary_Range', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '+' => array('precedence' => 30, 'class' => 'Twig_Node_Expression_Binary_Add', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '-' => array('precedence' => 30, 'class' => 'Twig_Node_Expression_Binary_Sub', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '~' => array('precedence' => 40, 'class' => 'Twig_Node_Expression_Binary_Concat', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '*' => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Mul', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '/' => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Div', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '//' => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_FloorDiv', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '%' => array('precedence' => 60, 'class' => 'Twig_Node_Expression_Binary_Mod', 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'is' => array('precedence' => 100, 'callable' => array($this, 'parseTestExpression'), 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                'is not' => array('precedence' => 100, 'callable' => array($this, 'parseNotTestExpression'), 'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT),
                '**' => array('precedence' => 200, 'class' => 'Twig_Node_Expression_Binary_Power', 'associativity' => \Twig_ExpressionParser::OPERATOR_RIGHT),
            ),
        );
    }
}
