<?php
require_once 'KiWiApi.php';




/**
 *  The main Parser class
 *
 *  Based on PHPHilighter
 *	@author Brandon Wamboldt <brandon.wamboldt@gmail.com>
 *  @license http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *  @link http://brandonwamboldt.ca/Parser
 * 
 *
 *  @author 
 *  @since 1.0.0
 */
class Parser
{
	

	/**
	 * 
	 *
	 * @param 
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct( )
	{

	}

	/**
	 * Highlights the given PHP and returns it as a string with HTML tags
	 *
	 * @return string
	 *
	 * @access public
	 * @since 1.0.0
	 */


	public function parse($source = "")
	{
		
		if(!empty($source))
		{
			$this->source = $source;
			$this->tokenize();
		}
		

		$in_namespace = FALSE;
		$in_class     = FALSE;
		$in_string    = FALSE;
		$output       = '';

		// Go through each token and make it into a tag
		foreach ( $this->tokens as $i => $token ) {

			// Certain tokens such as ; are returned by themselves, everything else is returned as
			// an array with three elements
			if ( is_array( $token ) ) {
				
				echo "TOkens:" << count($token);
				print_r($token);

				// So we don't have to call these functions all over
				$identifier = $token[0];
				$token_name = token_name( $token[0] );
				$token      = htmlspecialchars( $token[1] );
				$line = $token[2];

				// Don't enclose whitespace in a <span> tag, it gets excessive
				if ( $identifier === T_WHITESPACE ) {
					$output .= $token;
				}

				// Special handling for docblocks to deal with docblock tags, links and email links
				else if ( $identifier === T_DOC_COMMENT ) {

					//we are in doc comment now use that class to get proper
					// 
				}

				// Special handling for comments to deal with links and email links
				else if ( $identifier === T_COMMENT ) {

			
				}

				// The 'self' keyword is just tokenized as a string, we give it a special identifier
				else if ( $identifier === T_STRING && strtolower( $token ) == 'self' ) {
					$output .= '<span class="T_STRING C_SELF">' . $token . '</span>';
				}

				// The 'null' keyword is just tokenized as a string, we give it a special identifier
				else if ( $identifier === T_STRING && strtolower( $token ) == 'null' ) {
					$output .= '<span class="T_STRING C_NULL">' . $token . '</span>';
				}

				// The 'true' keyword is just tokenized as a string, we give it a special identifier
				else if ( $identifier === T_STRING && strtolower( $token ) == 'true' ) {
					$output .= '<span class="T_STRING C_TRUE">' . $token . '</span>';
				}

				// The 'false' keyword is just tokenized as a string, we give it a special identifier
				else if ( $identifier === T_STRING && strtolower( $token ) == 'false' ) {
					$output .= '<span class="T_STRING C_FALSE">' . $token . '</span>';
				}

				// Identify class method calls, like $this->myMethod(), not 100% successful
				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_OBJECT_OPERATOR && $this->next_token( $i ) === '(' ) {
					$output .= '<span class="T_STRING C_METHOD_CALL">' . $token . '</span>';
				}
			

				// Idenfy classnames
				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_CLASS ) {
					$this->api->callApi( 'updateOutlineModel', [$token, $line, 1] );
					$output .= '<span class="T_STRING C_CLASSNAME">' . $token . '</span>';
				}

				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_EXTENDS ) {
					$output .= '<span class="T_STRING C_EXTENDS_CLASS">' . $token . '</span>';
				}

				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_IMPLEMENTS ) {
					$output .= '<span class="T_STRING C_IMPLEMENTS_CLASS">' . $token . '</span>';
				}

				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_NEW ) {
					$output .= '<span class="T_STRING C_CLASSNAME_REF">' . $token . '</span>';
				}

				else if ( $identifier === T_STRING && $this->next_token( $i ) === T_VARIABLE ) {
					$output .= '<span class="T_STRING C_PARAMETER_TYPEHINT">' . $token . '</span>';
				}

				// Class properties ($var->property)
				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_OBJECT_OPERATOR && $this->next_token( $i ) !== '(' ) {
					$output .= '<span class="T_STRING C_OBJECT_PROPERTY">' . $token . '</span>';
				}

				// PHP Magic Method definitions
				else if ( $identifier === T_STRING && in_array( $token, self::$magic_methods ) ) {
					$output .= '<span class="T_STRING C_MAGIC_METHOD">' . $token . '</span>';
				}

				// Namespace declarations
				else if ( $identifier === T_STRING && ( $this->prev_token( $i ) === T_NAMESPACE 
				//|| $this->prev_token( $i ) === T_USE 
				
				) ) {
					$output .= '<span class="C_NAMESPACE"><span class="T_STRING">' . $token . '</span>';
					$in_namespace = TRUE;
					$this->api->callApi( 'updateOutlineModel', [$token, $line, 0] );
				}

				// Function names
				else if ( $identifier === T_STRING && $this->prev_token( $i ) === T_FUNCTION ) {
					$this->api->callApi( 'updateOutlineModel', [$token, $line, 2] );
					$output .= '<span class="T_STRING C_FUNCTION_NAME">' . $token.$line . '</span>';
				}

				// Builtin Functions
				else if ( $identifier === T_STRING && in_array( $token, Parser::$builtin_functions ) && $this->prev_token( $i ) !== T_FUNCTION ) {
					$output .= '<span class="T_STRING C_BUILTIN_FUNCTION">' . $token . '</span>';
				}

				// Special string handling
				else if ( $identifier === T_CONSTANT_ENCAPSED_STRING || $identifier === T_ENCAPSED_AND_WHITESPACE ) {
					$output .= '<span class="' . $token_name . '">' . preg_replace( '`(\\\[^ ])`', '<span class="C_DOUBLE_BACKSLASH">\1</span>', $token ) . '</span>';
				}

				// Start heredocs
				else if ( $identifier === T_START_HEREDOC ) {
					$output .= '<span class="T_START_HEREDOC"><span class="C_HEREDOC_ARROWS">&lt;&lt;&lt;</span>' .  str_replace( '&lt;&lt;&lt;', '', $token )  . '</span>';
				}

				// All other token
				else {
					$output .= '<span class="' . $token_name . '">' . $token . '</span>';
				}
			} else {

				// If we are in a namespace string and encounter a semicolon, end the namespace string
				if ( $token == ';' && $in_namespace ) {
					$output .= '</span><span class="C_SEMICOLON">;</span>';
					$in_namespace = FALSE;
				}

				// Give semicolons a name
				else if ( $token == ';' ) {
					$output .= '<span class="C_SEMICOLON">;</span>';
				}

				// Give assignment operators a name
				else if ( $token == '=' ) {
					$output .= '<span class="C_ASSIGNMENT">=</span>';
				}

				// Special handling for double quoted strings which may contain variables
				else if ( $token == '"' ) {
					if ( $in_string ) {
						$output .= '"</span>';
						$in_string = FALSE;
					} else {
						$output .= '<span class="C_VARSTRING">"';
						$in_string = TRUE;
					}
				}

				// All other single character tokens
				else {
					$output .= $token;
				}
			}
		}

		return '<pre class="pretty-php">' . $output . '</pre>';
	}



	

	/**
	 * If we haven't already got a list of builtin functions, get them and
	 * store them as a static variable
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function get_builtin_functions()
	{
		if ( Parser::$builtin_functions === NULL ) {
			Parser::$builtin_functions = array();
			$loaded_ext = get_loaded_extensions();

			foreach ( $loaded_ext as $ext ) {
				Parser::$builtin_functions = array_merge( Parser::$builtin_functions, (array) get_extension_funcs( $ext ) );
			}
		}
		
		//print_r(Parser::$builtin_functions);
	}

	/**
	 * Split given source into PHP tokens (PHP 4 >= 4.2.0, PHP 5)
	 *
	 * token_get_all() returns an array of tokens. Some array elements will be
	 * an array themselves, containing the token type, the PHP source for that
	 * token, and the line number that the token occured on. Other elements
	 * will simply be the token itself, such as (, ), [, and ]
	 *
	 * @link http://ca.php.net/manual/en/tokens.php
	 * @link http://ca.php.net/token_get_all
	 * @link http://ca.php.net/manual/en/function.token-name.php
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function tokenize()
	{
		$this->tokens = token_get_all( $this->source );
		//print_r($this->tokens);
	}

	/**
	 * Retrieve the next token from the array of tokens
	 *
	 * @param int $position The position in the token array
	 * @param int $modifier The token to get (Defaults to 1, for the next token)
	 * @param bool $significant optional Whether or not to retrieve only significant tokens (Not T_WHITESPACE)
	 * @return int
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function next_token( $position, $modifier = 1, $significant = TRUE )
	{
		// No tokens left
		if ( ! isset( $this->tokens[$position + $modifier] ) ) {
			return 0;
		}

		$token = $this->tokens[$position + $modifier];

		// Return the next significant (Non T_WHITESPACE) token?
		if ( $significant === TRUE ) {

			// If the requested token is a T_WHITESPACE token, call this function again but add 1 to the modifer
			if ( $this->tokens[$position + $modifier][0] === T_WHITESPACE ) {
				$token = $this->next_token( $position, $modifier + 1 );
			} else {
				$token = $this->tokens[$position + $modifier];
			}
		}

		// Return the next token
		if ( is_array( $token ) ) {
			return $token[0];
		}

		return $token;
	}

	/**
	 * Retrieve the previous token from the array of tokens
	 *
	 * @param int $position The position in the token array
	 * @param int $modifier The token to get (Defaults to 1, for the previous token)
	 * @param bool $significant optional Whether or not to retrieve only significant tokens (Not T_WHITESPACE)
	 * @return int
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function prev_token( $position, $modifier = 1, $significant = TRUE )
	{
		// No tokens left
		if ( ! isset( $this->tokens[$position - $modifier] ) ) {
			return 0;
		}

		$token = $this->tokens[$position - $modifier];

		// Return the next significant (Non T_WHITESPACE) token?
		if ( $significant === TRUE ) {

			// If the requested token is a T_WHITESPACE token, call this function again but add 1 to the modifer
			if ( $this->tokens[$position - $modifier][0] === T_WHITESPACE ) {
				$token = $this->prev_token( $position, $modifier + 1 );
			} else {
				$token = $this->tokens[$position - $modifier];
			}
		}

		// Return the next token
		if ( is_array( $token ) ) {
			return $token[0];
		} else {
			return $token;
		}
	}

	/**
	 * Outputs or returns a syntax highlighted version of the given PHP code using
	 * the colors defined in the included stylesheet
	 *
	 * @param string $source The PHP code to be highlighted. This should include the opening tag
	 * @param bool $return optional Set this parameter to TRUE to make this function return the highlighted code
	 * @return string|void
	 *
	 * @access public
	 * @since 1.0.0
	 * @static
	 */
	public static function highlight( $source, $return = FALSE, $options = 0 )
	{
		$p = new Parser( $source, $options );
		$str = $p->parse();

		if ( $return ) {
			return $str;
		}

		echo $str;
	}
}
